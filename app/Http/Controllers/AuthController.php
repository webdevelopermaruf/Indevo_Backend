<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpStatus;
use App\Http\DTO\ApiResponseData;
use App\Http\Requests\Registration;
use App\Mail\VerifyEmail;
use App\Models\EmailVerificationCode;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    /**
     * handles refresh token of logged in user.
     */
    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');
            $token = PersonalAccessToken::findToken($refreshToken);
            $user = $token->tokenable;

            // Revoke old access token & refresh tokens
            $user->tokens()->delete();

            // Issue new short-lived access token
            $newAccessToken = $user->createToken(
                'indevo-user-access-token',
                ['*'],
                now()->addMinutes(15)
            )->plainTextToken;

            $refreshToken = $user->createToken(
                'indevo-user-refresh-token',
                ['refresh'],
                now()->addDays(30)
            )->plainTextToken;


            return $this->success('Refreshed successfully', [
                'user' => [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age],
                'access_token' => $newAccessToken,
                'refresh_token' => $refreshToken,
                'token_type'   => 'Bearer',
                'expires_in'   => 900,
            ], HttpStatus::OK);

        } catch (\Exception|\Throwable $e) {
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * User login to the system
     */
    public function login(Request $request)
    {
        try{
            $req = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($req)) {
                return $this->error('Invalid credentials', code: 401);
            }

            $user  = Auth::user();
            $user->tokens()->delete();
            $accessToken = $user->createToken(
                'indevo-user-access-token',
                ['*'],
                now()->addMinutes(15)
            )->plainTextToken;

            $refreshToken = $user->createToken(
                'indevo-user-refresh-token',
                ['refresh'],
                now()->addDays(30)
            )->plainTextToken;


            return $this->success('Login Successful', [
                'user'  => [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age],
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type'   => 'Bearer',
                'expires_in'   => 900,
            ]);
        }catch (\Exception $e){
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
        }
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
            $code = random_int(100000, 999999);
            $user = DB::transaction(function () use ($req, $code) {
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

                EmailVerificationCode::updateOrCreate(
                    ['email' => $user->email],
                    ['code' => $code, 'expires_at' => now()->addMinutes(15)]
                );

                return $user;
            });

            $token = $user->createToken('indevo-user-access-token')->plainTextToken;
            Mail::to($user->email)->send(new VerifyEmail($user->email, $code));
            return $this->success(
                'User registered successfully',
                [
                    'user' => [...$user->only(['firstname', 'lastname', 'email', 'dob', 'currency']), 'age' => $user->age],
                    'access-token' => $token,
                    'token_type'   => 'Bearer',
                    'expires_in'   => 900,
                ],HttpStatus::CREATED
            );

        } catch (\Exception $e) {
            return $this->error(
                'Registration failed', null,
                HttpStatus::INTERNAL_SERVER_ERROR
            );
        }
    }

    public function verifyEmail(Request $request) : ApiResponseData
    {
       try{

           $validated_data = $request->validate([
               'email' => 'required|email',
               'code'  => 'required|integer'
           ]);

           $check = EmailVerificationCode::where('email', $validated_data['email'])->where('code', $validated_data['code'])->first();
           if($check){
               $user = User::where('email', $validated_data['email'])->update(['email_verified_at' => now()]);
               return $this->success('Email verified successfully', null,HttpStatus::OK);
           }else{
               return $this->error('Email Unverified', null, HttpStatus::INTERNAL_SERVER_ERROR);
           }

       }catch(\Exception $e){
           return $this->error(
               'Email Verification failed', null,
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
                $token = $user->createToken('indevo-user-access-token')->plainTextToken;
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
     * user forgot password via email verification.
     */
    public function forgot(Request $request): ApiResponseData{
        try{
            $validated_data = $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $code = random_int(100000, 999999);

            ForgotPassword::updateOrCreate(
                ['email' => $validated_data['email']],
                ['code' => $code, 'expires_at' => now()->addMinutes(15), 'updated_at' => now(), 'created_at' => now()]
            );
    //            Mail::to($validated_data['email'])->send(new VerifyEmail($validated_data['email'], $code));
            return $this->success('Email Sent', null, HttpStatus::OK);
        }catch(\Exception $e){
            return $this->error(
                'Something went wrong', null,
                HttpStatus::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * user logout via token deletion.
     */

    public function logout(Request $request)
    {
        try{
            $request->user()->tokens()->delete();
        }catch(\Exception $e){
            return $this->error('Something went wrong', null, HttpStatus::INTERNAL_SERVER_ERROR);
        }finally{
            return $this->success('Logged out successfully', null, HttpStatus::OK);
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
