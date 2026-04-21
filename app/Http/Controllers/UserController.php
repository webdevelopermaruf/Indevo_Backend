<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        return $this->success('User data fetch successfully',
            [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age]
        , HttpStatus::OK);
    }

    /**
     * Name Change function
     */
    public function nameChange(Request $request)
    {
        try{
            $validated = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
            ]);
            $user = auth()->user();
            $user->firstname =$validated['firstname'];
            $user->lastname =$validated['lastname'];
            $user->save();
            return $this->success('Name Updated', $user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), HttpStatus::OK);
        }catch(\Exception $e){
            return $this->error("Something went wrong", null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * password change function
     */
    public function passwordChange(Request $request)
    {
        try{
            $validated = $request->validate([
                'current_password'          => ['required', 'current_password'],
                'new_password'              => ['required', 'string', 'min:8', 'different:current_password', 'confirmed'],
            ]);

            $user = $request->user();

            $user->forceFill([
                'password' => Hash::make($validated['new_password']),
            ])->save();

            // Revoke all existing tokens (Sanctum). Remove if you want to keep other sessions.
            $user->tokens()->delete();

            return $this->success('Password updated successfully', null, HttpStatus::OK);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function preference(Request $request)
    {
        try{
            $validated = $request->validate([
                'dark_mode' => 'required|boolean',
                'push_notification' => 'required|boolean',
                'reminder_alert' => 'required|boolean',
                'goal_deadline_alert' => 'required|boolean',
            ]);

            $user = auth()->user();
            $user->forceFill([
                'preference' => json_encode([
                    'dark_mode' => $validated['dark_mode'],
                    'push_notification' => $validated['push_notification'],
                    'reminder_alert' => $validated['reminder_alert'],
                    'goal_deadline_alert' => $validated['goal_deadline_alert'],
                ], true),
            ]);

            $user->save();
            return $this->success('Preference updated successfully', null, HttpStatus::OK);
        }catch(\Exception $e){
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
        }


    }

}
