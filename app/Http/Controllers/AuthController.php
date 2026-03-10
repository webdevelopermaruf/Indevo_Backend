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
                    'user' => [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age],
                    'access-token' => $token
                ],HttpStatus::CREATED
            );

        } catch (\Exception $e) {
            return $this->error(
                'Registration failed', null,
                HttpStatus::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * handles OAuth redirect from Google exchanges code for access token, gets user info and login the user.
     */
    public function googleLogin(Request $request): ApiResponseData
    {
        try {
            $request->validate([
                'id_token' => 'required|string',
            ]);

            $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return $this->error('Invalid Google token', null, 401);
            }

            $googleId = $payload['sub'];
            $email    = $payload['email'];
            $name     = $payload['name'] ?? null;

            $user = User::where('google_id', $googleId)->orWhere('email', $email)->first();

            if ($user) {
                // If user exists, log in
                $token = $user->createToken('api-token')->plainTextToken;
                return $this->success('Login successful', [
                    'user'  => $user->only(['firstname','lastname','email','role']),
                    'token' => $token
                ]);
            }

            // User doesn't exist → require additional info
            return $this->success('Additional info required', [
                'google' => [
                    'google_id' => $googleId,
                    'email'     => $email,
                    'firstname' => $name ? explode(' ', $name)[0] : null,
                    'lastname'  => $name ? explode(' ', $name, 2)[1] ?? null : null,
                ]
            ], 206); // 206 Partial Content
        }catch (\Exception $e) {
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
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
