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
     * Display a listing of the goals.
     */
    public function index()
    {
        try{
            // Getting all goals for this month
            $this_month_goals = Goal::currentMonth()->get();
            return $this->success('This month goals information', $this_month_goals->toArray());
        }catch(\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GoalRequest $request)
    {
        try{
            $req = $request->validated();
            $reminderIds = $req['reminders'];

            $alreadyAssigned = Reminder::whereIn('id', $reminderIds)
                ->whereNotNull('goal_id')
                ->exists();

            if ($alreadyAssigned) {
                return $this->error('Reminders are already in goal', null, HttpStatus::BAD_REQUEST);
            }
            $goal = DB::transaction(function () use ($req, $reminderIds) {
                $goal = Goal::create([
                    ...Arr::except($req, ['reminders']),
                    'user_id' => auth()->id(),
                ]);
                Reminder::whereIn('id', $reminderIds)
                    ->update(['goal_id' => $goal->id]);
                return $goal;
            });

            return $this->success('Goal created', $goal->toResponseArray(), HttpStatus::CREATED);

        }catch(\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(GoalRequest $request)
    {
        $req = $request->validated();
        try{
            $id =  intval($req['id']);
            $reminderIds = $req['reminders']; // already array from validation rules

            $alreadyAssigned = Reminder::whereIn('id', $reminderIds)
                ->whereNot('goal_id', null)
                ->whereNot('goal_id', $id)
                ->exists();

            if ($alreadyAssigned) {
                return $this->error('Reminders are already in goal', null, HttpStatus::BAD_REQUEST);
            }
            $goal = DB::transaction(function () use ($id, $req, $reminderIds) {
                $goal = Goal::where('id', $id)->update([...Arr::except($req, ['reminders'])]);

                // update the reminders
                Reminder::whereIn('id', $reminderIds)
                    ->update(['goal_id' => $id]);

                // eliminate the reminders from goal
                Reminder::whereNotIn('id', $reminderIds)->where('goal_id', $id)->update(['goal_id' => null]);
                return $req;
            });
            return $this->success('Goal updated', $goal, HttpStatus::OK);
        }catch(\Exception $exception){
            return $this->error($exception->getMessage(), [
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ], HttpStatus::INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        //
    }
}
