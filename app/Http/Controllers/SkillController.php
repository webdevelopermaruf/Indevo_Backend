<?php
namespace App\Http\Controllers;
use App\Http\Constants\HttpStatus;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\Request;
class SkillController extends Controller
{
    /**
     * Display a listing of all resources for skills page.
     */
    public function index()
    {
        try{
            $userId = auth()->id();
            $skills = Skill::withCount('steps')->orderBy('updated_at', 'desc')->paginate(50);

            // Get all completed step IDs for this user
            $completedStepIds = UserSkill::where('user_id', $userId)
                ->where('is_completed', 1)
                ->pluck('skill_steps_id')
                ->toArray();

            // Mark each skill as completed if all steps are done
            $skillItems = collect($skills->items())->map(function($skill) use ($completedStepIds) {
                $stepIds = $skill->steps()->pluck('id')->toArray();
                $completedCount = count(array_intersect($stepIds, $completedStepIds));
                $skill->completed_steps = $completedCount;
                $skill->is_completed = $skill->steps_count > 0 && $completedCount >= $skill->steps_count;
                return $skill;
            });

            return $this->success('Available Skills', [
                'skills' => $skillItems,
                'meta' => [
                    'current_page' => $skills->currentPage(),
                    'last_page'    => $skills->lastPage(),
                    'per_page'     => $skills->perPage(),
                    'total'        => $skills->total(),
                    'next'         => $skills->nextPageUrl(),
                    'prev'         => $skills->previousPageUrl(),
                ],
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified skills information.
     */
    public function show(string $id)
    {
        try {
            $userId = auth()->id();
            $data = Skill::with(['steps' => function($query) use ($userId) {
                $query->with(['status' => function($q) use ($userId) {
                    $q->where('user_id', $userId);
                }]);
            }])->findOrFail($id);

            $responseArray = $data->toResponseArray();

            // Add completion status to each step
            $responseArray['steps'] = collect($responseArray['steps'])->map(function($step) {
                $stepArr = is_array($step) ? $step : $step->toArray();
                $stepArr['is_completed'] = isset($stepArr['status']) && $stepArr['status'] !== null && $stepArr['status']['is_completed'] == 1;
                return $stepArr;
            });

            // Check if whole skill is completed
            $allCompleted = collect($responseArray['steps'])->every(fn($s) => $s['is_completed']);
            $responseArray['is_completed'] = $allCompleted;
            $responseArray['completed_steps'] = collect($responseArray['steps'])->filter(fn($s) => $s['is_completed'])->count();

            return $this->success('Skill Detail', $responseArray, HttpStatus::OK);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified user skill completed record.
     */
    public function update(Request $request)
    {
        try{
            $req = $request->validate([
                'step_id' => 'required',
                'action'  => 'required'
            ]);

            if($req['action'] === 'update'){
                // Check if already exists to avoid duplicates
                $existing = UserSkill::where('user_id', auth()->id())
                    ->where('skill_steps_id', $req['step_id'])
                    ->first();

                if(!$existing){
                    UserSkill::insert([
                        'skill_steps_id' => $req['step_id'],
                        'user_id'        => auth()->id(),
                        'is_completed'   => 1,
                        'completed_at'   => now(),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                return $this->success('User skill record updated', null, HttpStatus::OK);
            }else{
                return $this->error('Bad Request', null, HttpStatus::BAD_REQUEST);
            }
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}