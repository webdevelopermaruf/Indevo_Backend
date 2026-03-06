<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpStatus;
use App\Http\DTO\ApiResponseData;
use App\Http\Requests\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User login to the system
     */
    public function login(Request $request)
    {
        $req = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($req)) {
            return $this->error('Invalid credentials', code: 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('indevo-user-access-token')->plainTextToken;
        return $this->success('Login Successful', [
            'user'  => [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age],
            'token' => $token,
        ]);
    }

    /**
     * Before going to registration check the user email.
     */
    public function checkEmail(Request $request) : ApiResponseData
    {
        $req = $request->validate([
            'email' => 'required|email'
        ]);

        $searchUser = User::where('email', $req['email'])->count();
        if($searchUser){
            return $this->success("User Found");
        }else{
            return $this->error("User Not Found");
        }
    }

    /**
     * User Registration to the system.
     */
    public function register(Registration $request) : ApiResponseData
    {
        try {

            $req = $request->validated();
            $user = User::create([
                'firstname' => $req['firstname'],
                'lastname'  => $req['lastname'],
                'email'     => $req['email'],
                'dob'       => $req['dob'],
                'currency'  => $req['currency'],
                'password'  => Hash::make($req['password']),
                'hobbies'   => $req['hobbies'] ?? null,
                'role'      => 'user'
            ]);
            $token = $user->createToken('api-token')->plainTextToken;
            return $this->success(
                'User registered successfully',
                [
                    'user' => $user->only(['firstname','lastname','email','dob','role']),
                    'access-token' => $token
                ],
                HttpStatus::CREATED
            );

        } catch (\Exception $e) {

            return $this->error(
                'Registration failed', null,
                HttpStatus::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * if unauthorised then return json error
     */
    public function unauthorised()
    {
        return $this->error('Unauthorised',null, HttpStatus::UNAUTHORIZED);
    }
}
