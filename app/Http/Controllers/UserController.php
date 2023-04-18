<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Params;
use Illuminate\Http\Request;
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
        $request->validate(['name' => 'required|string', 'email' => 'required|string|unique:users,email', 'password' => 'required|string']);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if($newUser = User::factory()->create(['name' => $name, 'email' => $email, 'password' => $password]))
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
                return response('Error while creating user token', 404);
            }
            //return ('Successfully created new user');
        }
        else
        {
            return response('Error while creating new user', 404);
        }
    }
    public function resetPassword(Request $request, $id)
    {
        $request->validate(['name' => 'required|string', 'password' => 'required|string']);

        $name = $request->name;
        $password = bcrypt($request->password);
        if (User::find($id)->update([
            'name' => $name,
            'password' => $password
        ]))
        {
            return response("User successfully updated", 200);
        }
        else
        {
            return response("Error while updating user", 404);
        }
    }

    public function deleteUser($id)
    {
        if (User::find($id)->delete())
        {
            return response('User successfully deleted', 200);
        }
        else
        {
            return response('Error while deleting user', 404);
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
                return ("Successful Sign In ");
            }
            else
            {
                return response("Wrong Password", 401);
            }
        }
        else
        {
            return response('User not found', 401);
        }
    }
}
