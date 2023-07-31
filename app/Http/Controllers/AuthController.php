<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Params;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if($newUser = User::create(['name' => $name, 'email' => $email, 'password' => $password]))
        {
            $data = [
                'grant_type' => 'password',
                'client_id'  => config('app.client_secret_id'),
                'client_secret' => config('app.client_secret'),
                'username' => $request->email,
                'password' => $request->password
            ];
            $passportRequest = Request::create('/oauth/token', 'POST', $data);
            $content = json_decode(app()->handle($passportRequest)->getContent());

            return response()->json($content);
        }
        else
        {
            return response()->json(["message" => "Error while creating new user"], Response::HTTP_NOT_FOUND);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Wrong username or password"], Response::HTTP_UNAUTHORIZED);
        }

        $data = [
            'grant_type' => 'password',
            'client_id'  => config('app.client_secret_id'),
            'client_secret' => config('app.client_secret'),
            'username' => $request->email,
            'password' => $request->password
        ];
        $passportRequest = Request::create('/oauth/token', 'POST', $data);
        $content = json_decode(app()->handle($passportRequest)->getContent());

        return response()->json($content);
    }

    public function logout()
    {
        auth()->user()?->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh(Request $refreshRequest): JsonResponse
    {
        $refreshRequest->validate(
            [
                'refresh_token' => 'required|string|max:1000',
            ]
        );

        $data = [
            'grant_type'    => 'refresh_token',
            'client_id'     => config('app.client_secret_id'),
            'client_secret' => config('app.client_secret'),
            'refresh_token' => $refreshRequest->refresh_token,
        ];
        $passportRequest = Request::create('/oauth/token', 'POST', $data);
        $content = json_decode(app()->handle($passportRequest)->getContent());

        return response()->json($content);
    }
}
