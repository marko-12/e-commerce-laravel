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
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() : Collection
    {
        return User::all();
    }
    public function show($id)
    {
        //return $user = DB::table('users')->where('id', $id)->first();
        return $user = User::where('id',$id)->first();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            //'password' => 'required|string'
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = bcrypt($request->password);
        if (User::find($id)->update([
            'name' => $name,
            'email' =>$email,
            //'password' => $password
        ]))
        {
            $updatedUser = User::find($id);
            return response()->json(["message" => "User successfully updated", "updated_user" => $updatedUser], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while updating user"], Response::HTTP_NOT_FOUND);
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8'
        ]);

        $password = bcrypt($request->password);

        if (User::find($id)->update([
            'password' => $password
        ]))
        {
            return response()->json(["message" => "Password reset successfully"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while resetting password"], Response::HTTP_NOT_FOUND);
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

    public function destroy($id)
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
    public function userInfo()
    {
//        if ($user = User::where('email', $request->email)->first())
//        {
//            return response()->json($user);
//        }
//        else
//        {
//            return response()->json(["message" => "Error while getting user info"], Response::HTTP_NOT_FOUND);
//        }
        return response()->json(auth()->user());
    }
}
