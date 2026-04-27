<?php
namespace App\Http\Controllers;
use App\Http\Constants\HttpStatus;
use App\Models\UserSkill;
use App\Models\SkillStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Calculate user XP and level from completed skill steps
     */
    private function getUserXpData($userId): array
    {
        // Get all completed step IDs for this user
        $completedStepIds = UserSkill::where('user_id', $userId)
            ->pluck('skill_steps_id')
            ->unique();

        // Sum rewards from the skills those steps belong to
        $totalXp = 0;
        $completedSkillIds = [];

        foreach ($completedStepIds as $stepId) {
            $step = SkillStep::with('skill')->find($stepId);
            if ($step && $step->skill) {
                // Count each skill's reward once per completed step
                // (reward is per skill completion, but we track per step)
                $totalXp += ($step->skill->reward / max($step->skill->steps()->count(), 1));
                $completedSkillIds[] = $step->skill_id;
            }
        }

        $totalXp = (int) round($totalXp);

        // Level thresholds: each level needs more XP
        $levels = [
            1  => ['name' => 'Beginner',       'min' => 0,    'max' => 100],
            2  => ['name' => 'Explorer',        'min' => 100,  'max' => 250],
            3  => ['name' => 'Learner',         'min' => 250,  'max' => 500],
            4  => ['name' => 'Achiever',        'min' => 500,  'max' => 800],
            5  => ['name' => 'Skill Builder',   'min' => 800,  'max' => 1200],
            6  => ['name' => 'Skill Seeker',    'min' => 1200, 'max' => 1700],
            7  => ['name' => 'Skill Master',    'min' => 1700, 'max' => 2300],
            8  => ['name' => 'Expert',          'min' => 2300, 'max' => 3000],
            9  => ['name' => 'Champion',        'min' => 3000, 'max' => 4000],
            10 => ['name' => 'Legend',          'min' => 4000, 'max' => 999999],
        ];

        $currentLevel = 1;
        $levelData = $levels[1];

        foreach ($levels as $lvl => $data) {
            if ($totalXp >= $data['min']) {
                $currentLevel = $lvl;
                $levelData = $data;
            }
        }

        $nextLevel = $currentLevel + 1;
        $nextLevelData = $levels[$nextLevel] ?? null;
        $nextLevelXp = $nextLevelData ? $nextLevelData['min'] : $levelData['max'];

        return [
            'total_xp'         => $totalXp,
            'level'            => $currentLevel,
            'level_name'       => $levelData['name'],
            'current_level_xp' => $levelData['min'],
            'next_level_xp'    => $nextLevelXp,
            'completed_steps'  => count($completedStepIds),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $xpData = $this->getUserXpData($user->id);

        return $this->success('User data fetch successfully', [
            ...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']),
            'age' => $user->age,
            'xp'  => $xpData,
        ], HttpStatus::OK);
    }

    /**
     * Name Change function
     */
    public function nameChange(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required',
                'lastname'  => 'required',
            ]);
            $user = auth()->user();
            $user->firstname = $validated['firstname'];
            $user->lastname  = $validated['lastname'];
            $user->save();
            return $this->success('Name Updated', $user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), HttpStatus::OK);
        } catch (\Exception $e) {
            return $this->error("Something went wrong", null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Password change function
     */
    public function passwordChange(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password'     => ['required', 'string', 'min:8', 'different:current_password', 'confirmed'],
            ]);
            $user = $request->user();
            $user->forceFill([
                'password' => Hash::make($validated['new_password']),
            ])->save();
            $user->tokens()->delete();
            return $this->success('Password updated successfully', null, HttpStatus::OK);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function preference(Request $request)
    {
        try {
            $validated = $request->validate([
                'currency'           => 'required',
                'dark_mode'          => 'required|boolean',
                'push_notification'  => 'required|boolean',
                'reminder_alert'     => 'required|boolean',
                'goal_deadline_alert'=> 'required|boolean',
            ]);
            $user = auth()->user();
            $user->forceFill([
                'currency'   => $validated['currency'],
                'preference' => json_encode([
                    'dark_mode'          => $validated['dark_mode'],
                    'push_notification'  => $validated['push_notification'],
                    'reminder_alert'     => $validated['reminder_alert'],
                    'goal_deadline_alert'=> $validated['goal_deadline_alert'],
                ], true),
            ]);
            $user->save();
            return $this->success('Preference updated successfully', null, HttpStatus::OK);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}