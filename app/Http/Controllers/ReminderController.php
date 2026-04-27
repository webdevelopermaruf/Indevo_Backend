<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpStatus;
use App\Http\Requests\ReminderRequest;
use App\Models\Reminder;
use App\Models\ScheduledNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReminderController extends Controller
{
    // Display a today reminders in the system.

    public function index(){

        try {
            $today = now()->toDateString();

            $todayTasks = Reminder::query()
                ->whereDate('due_date', $today)
                ->orderBy('due_time', 'asc')
                ->get();

            $upcomingTasks = Reminder::query()
                ->where('is_completed', false)
                ->whereDate('due_date', '>', $today)
                ->orderBy('due_date', 'asc')
                ->orderBy('due_time', 'asc')
                ->get();

            $data = [
                'today'    => $todayTasks->groupBy(function ($task) {
                    $hour = (int) date('H', strtotime($task->due_time));
                    return match(true) {
                        $hour >= 5  && $hour < 12 => 'this_morning',
                        $hour >= 12 && $hour < 17 => 'this_afternoon',
                        $hour >= 17 && $hour < 21 => 'this_evening',
                        default                   => 'tonight',
                    };
                }),
                'upcoming' => $upcomingTasks->groupBy(function ($task) {
                    return $task->due_date;
                }),
            ];

            return $this->success('Reminders', $data);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    // Inserting new expenses in the system.

    public function store(ReminderRequest $request)
    {
        try {
            $req = $request->validated();
            $reminder = Reminder::create([
                ...$req,
                'user_id' => auth()->id(),
            ]);
            if (auth()->user()->is_reminder_alert) {
                $this->scheduleNotifications($reminder);
            }

            return $this->success('Reminder created', $reminder->toResponseArray(), HttpStatus::CREATED);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    protected function scheduleNotifications(Reminder $reminder): void
    {
        $sendAtList = $this->resolveSendAtDates($reminder);

        $title = '⏰ Reminder: ' . $reminder->description;
        $body  = $this->buildReminderBody($reminder);

        foreach ($sendAtList as $sendAt) {
            ScheduledNotification::create([
                'user_id' => $reminder->user_id,
                'title'   => $title,
                'body'    => $body,
                'send_at' => $sendAt,
            ]);
        }
    }

    /**
     * Build the list of send_at datetimes based on recurrence.
     *
     * @return Carbon[]
     */
    protected function resolveSendAtDates(Reminder $reminder): array
    {
        $time = $reminder->due_time ?? '09:00';
        [$hour, $minute] = array_map('intval', explode(':', $time));

        $start = Carbon::parse($reminder->due_date)->setTime($hour, $minute);
        $now   = now();

        return match ($reminder->recurrence) {
            'once'   => $start->isPast() ? [] : [$start],
            'daily'  => $this->datesUntilMonthEnd($start, $now),
            'weekly' => $this->weeklyDatesUntilMonthEnd($start, $now),
        };
    }

    /**
     * Daily occurrences from start through the end of the current month.
     */
    protected function datesUntilMonthEnd(Carbon $start, Carbon $now): array
    {
        $endOfMonth = $now->copy()->endOfMonth();
        $cursor     = $start->copy();
        $dates      = [];

        while ($cursor->lte($endOfMonth)) {
            if ($cursor->gte($now)) {
                $dates[] = $cursor->copy();
            }
            $cursor->addDay();
        }

        return $dates;
    }

    /**
     * Weekly occurrences (same weekday as due_date) through the end of the current month.
     */
    protected function weeklyDatesUntilMonthEnd(Carbon $start, Carbon $now): array
    {
        $endOfMonth = $now->copy()->endOfMonth();
        $cursor     = $start->copy();
        $dates      = [];

        while ($cursor->lte($endOfMonth)) {
            if ($cursor->gte($now)) {
                $dates[] = $cursor->copy();
            }
            $cursor->addWeek();
        }

        return $dates;
    }

    protected function buildReminderBody(Reminder $reminder): string
    {
        $when = Carbon::parse($reminder->due_date)->format('M j, Y');
        if ($reminder->due_time) {
            $when .= ' at ' . Carbon::parse($reminder->due_time)->format('g:i A');
        }

        $parts = [$when];

        if ($reminder->place) {
            $parts[] = '📍 ' . $reminder->place;
        }

        if ($reminder->recurrence !== 'once') {
            $parts[] = 'Repeats ' . $reminder->recurrence;
        }

        return implode(' • ', $parts);
    }

    // update the reminder complete status

    public function markAsCompleted(Request $request){
        try{
            $req = $request->validate([
                'action' => 'required',
                'reminder_id' => 'required'
            ]);

            $reminder = Reminder::findOrFail($req['reminder_id']);

            if ($reminder->is_completed) {
                $completedDate = Carbon::parse($reminder->completed_at);
                $today         = Carbon::today();

                if ($reminder->recurrence !== 'once' && $completedDate->lt($today)) {
                    $reminder->is_completed = true;
                    $reminder->completed_at = now();
                    $reminder->save();
                } else {
                    return $this->error('Action already completed', null, HttpStatus::INTERNAL_SERVER_ERROR);
                }
            } else {
                $reminder->is_completed = true;
                $reminder->completed_at = now();
                $reminder->save();
            }
            return $this->success('Reminder marked as completed', $reminder->toResponseArray(), HttpStatus::CREATED);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy($id)
{
    try {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();
        return $this->success('Reminder deleted', null, HttpStatus::OK);
    } catch (\Exception $exception) {
        return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
    }
}
}
