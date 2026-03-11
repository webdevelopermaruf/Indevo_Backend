<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpStatus;
use App\Http\Requests\ReminderRequest;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function store(ReminderRequest $request){
        try {
            $req = $request->validated();
            $reminder = Reminder::create([
                ...$req,
                'user_id' => auth()->id(),
            ]);
            return $this->success('Reminder created', $reminder->toResponseArray(), HttpStatus::CREATED);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
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
}
