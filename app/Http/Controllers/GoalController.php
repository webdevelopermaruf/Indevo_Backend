<?php
namespace App\Http\Controllers;
use App\Http\Constants\HttpStatus;
use App\Http\Requests\GoalRequest;
use App\Models\Goal;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class GoalController extends Controller
{
    /**
     * Display a listing of all goals.
     */
    public function index()
    {
        try {
            $goals = Goal::with('reminders')->orderBy('created_at', 'desc')->get();
            return $this->success('Goals', $goals->map->toResponseArray()->toArray());
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created goal.
     */
    public function store(GoalRequest $request)
    {
        try {
            $req = $request->validated();
            $reminderIds = $req['reminders'] ?? [];

            if (!empty($reminderIds)) {
                $alreadyAssigned = Reminder::whereIn('id', $reminderIds)
                    ->whereNotNull('goal_id')
                    ->exists();
                if ($alreadyAssigned) {
                    return $this->error('Reminders are already in goal', null, HttpStatus::BAD_REQUEST);
                }
            }

            $goal = DB::transaction(function () use ($req, $reminderIds) {
                $goal = Goal::create([
                    ...Arr::except($req, ['reminders']),
                    'user_id' => auth()->id(),
                ]);
                if (!empty($reminderIds)) {
                    Reminder::whereIn('id', $reminderIds)->update(['goal_id' => $goal->id]);
                }
                return $goal;
            });

            $goal->load('reminders');
            return $this->success('Goal created', $goal->toResponseArray(), HttpStatus::CREATED);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified goal (including marking as completed).
     */
    public function update(GoalRequest $request)
    {
        $req = $request->validated();
        try {
            $id = intval($req['id']);
            $reminderIds = $req['reminders'] ?? [];

            if (!empty($reminderIds)) {
                $alreadyAssigned = Reminder::whereIn('id', $reminderIds)
                    ->whereNotNull('goal_id')
                    ->where('goal_id', '!=', $id)
                    ->exists();
                if ($alreadyAssigned) {
                    return $this->error('Reminders are already in goal', null, HttpStatus::BAD_REQUEST);
                }
            }

            DB::transaction(function () use ($id, $req, $reminderIds) {
                $updateData = Arr::except($req, ['reminders', 'id']);

                // Handle completion
                if (!empty($updateData['is_completed'])) {
                    $updateData['completion_date'] = now();
                }

                Goal::where('id', $id)->update($updateData);

                if (!empty($reminderIds)) {
                    Reminder::whereIn('id', $reminderIds)->update(['goal_id' => $id]);
                }
                Reminder::whereNotIn('id', $reminderIds)->where('goal_id', $id)->update(['goal_id' => null]);
            });

            $goal = Goal::with('reminders')->find($id);
            return $this->success('Goal updated', $goal->toResponseArray(), HttpStatus::OK);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a specific goal.
     */
    public function destroy($id)
    {
        try {
            $goal = Goal::findOrFail($id);
            // Unlink reminders before deleting
            Reminder::where('goal_id', $id)->update(['goal_id' => null]);
            $goal->delete();
            return $this->success('Goal deleted', null, HttpStatus::OK);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}