<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUsers() : Collection
    {
        return User::all();
    }
    public function getUserById($id)
    {
        //return $user = DB::table('users')->where('id', $id)->first();
        return $user = User::where('id',$id)->first();
    }
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if($newUser = User::create(['name' => $name, 'email' => $email, 'password' => $password]))
        {
            if ($token = $newUser->createToken("access_token", ["login"]))
            {
                return [
                    'user' => $newUser,
                    'token' => $token->plainTextToken
                ];
            }
            else
            {
                return response()->json(["message" => "Error while creating user token"], Response::HTTP_NOT_FOUND);
            }
            //return ('Successfully created new user');
        }
        else
        {
            return response()->json(["message" => "Error while creating new user"], Response::HTTP_NOT_FOUND);
        }
    }
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if (User::find($id)->update([
            'name' => $name,
            'email' =>$email,
            'password' => $password
        ]))
        {
            $updatedUser = User::find($id);
            return response()->json($updatedUser, Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while updating user"], Response::HTTP_NOT_FOUND);
        }
    }

    public function changeUser(Request $request, $id)
    {
        if ($user = User::find($id))
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'isAdmin' => $request->isAdmin
            ]);
            return response()->json(["message" => "User successfully updated !"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "User not found"], Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteUser($id)
    {
        if (User::find($id)->delete())
        {
            return response()->json(["message" => "User successfully deleted"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while deleting user"], Response::HTTP_NOT_FOUND);
        }
    }
    public function signIn(Request $request)
    {
        $request->validate(['email' => 'required|string', 'password' => 'required|string']);

        $email = $request->email;
        if ($user = User::where('email', $email)->first())
        {
            if (Hash::check($request->password, $user->password))
            {
                return response()->json($user);
            }
            else
            {
                return response()->json(["message" => "Wrong Password"], Response::HTTP_UNAUTHORIZED);
            }
        }
        else
        {
            return response()->json(["message" => "User not found"], Response::HTTP_UNAUTHORIZED);
        }
    }
}
