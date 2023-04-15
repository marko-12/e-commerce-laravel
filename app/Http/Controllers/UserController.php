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
        $request->validate(['name' => 'required|string', 'email' => 'required|string', 'password' => 'required|string']);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if($newUser = User::factory()->create(['name' => $name, 'email' => $email, 'password' => $password]))
        {
            return ('Successfully created new user');
        }
        else
        {
            return ('Error while creating new user');
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
            return ("User successfully updated");
        }
        else
        {
            return ("Error while updating user");
        }
    }

    public function deleteUser($id)
    {
        if (User::find($id)->delete())
        {
            return ('User successfully deleted');
        }
        else
        {
            return ('Error while deleting user');
        }
    }
    public function signIn(Request $request)
    {
        $request->validate(['email' => 'required|string', 'password' => 'required|string']);

        $email = $request->email;
        if ($user = User::where('email', $email)->first())
        {
            $password = $request->password;
            if (Hash::check($password, $user->password))
            {
                return ("Successful Sign In ");
            }
            else
            {
                return ("Wrong Password");
            }
        }
        else
        {
            return ('User not found');
        }
    }
}
