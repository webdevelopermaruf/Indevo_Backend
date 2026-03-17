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
            $skills = Skill::withCount('steps')->orderBy('updated_at', 'desc')->paginate(5);
            return $this->success('Available Skills', [
                'skills' => $skills->items(),
                'meta' => [
                    'current_page' => $skills->currentPage(),
                    'last_page' => $skills->lastPage(),
                    'per_page' => $skills->perPage(),
                    'total' => $skills->total(),
                    'next' => $skills->nextPageUrl(),
                    'prev' => $skills->previousPageUrl(),
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
            $data = Skill::with('steps.status')->findOrFail($id);
            return $this->success('Skill Detail', $data->toResponseArray(), HttpStatus::OK);
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
                'step_id'  => 'required',
                'action' => 'required'
            ]);

            UserSkill::insert([
                'skill_id' => $req['skill_id'],
                'user_id' => auth()->id(),
                'is_completed' => $req['action'] === 'completed' ? 1 : 0,
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->success('User skill record updated', $req['action'], HttpStatus::OK);

        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

}
