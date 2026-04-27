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
            $goals = Reminder::whereMonth('due_date', date('m'))->get();
            $completed = 0;
            $total = $goals->count();
            foreach ($goals as $goal) {
                if($goal->is_completed == 1) {
                    $completed++;
                }
            }
            $pending = $total - $completed;
            return $this->success('Goals', [
                'completed' => $completed,
                'total' => $total,
                'pending' => $pending,
                'percentage' => intval($completed / $total * 100),
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}
