<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['users' => User::where('type', '>', 0)->paginate(10)]);
    }

    public function update(Request $request, $id)
    {
        $userParams = $request->only('name', 'phone', 'address', 'email', 'type');

        $rules = [
            'name' => 'required',
            'email' => 'email|max:255|required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'unique:users|required',
            'type' => 'required',
            'address' => 'required'
        ];

        $validator = Validator::make($userParams, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }
        $user = User::find($id);
        if($user) {
            $user->update($userParams);
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 400);
        }
        
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        if($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 400);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if($user) {
            $user->softDeletes();
            return response()->json(['message' => 'Delete user ' . $id . ' successfully']);
        } else {
            return response()->json(['message' => 'User not found'], 400);
        }
        
    }
}
