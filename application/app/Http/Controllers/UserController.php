<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        return response()->json(['users' => User::where('type', '>', 0)->paginate(10)]);
    }

    public function update(Request $request, $id) {
        $user_params = $request->only('name', 'phone', 'address', 'email', 'type');
        $user = User::find($id);
        $user->update($user_params);
        return response()->json(['user' => $user]);
    }

    public function show(Request $request, $id) {
        $user = User::find($id);
        return response()->json(['user' => $user]);
    }

    public function delete(Request $request, $id) {
        $user = User::find($id);
        $user->softDeletes();
        return response()->json(['message' => 'Delete user ' . $id . ' successfully']);
    }
}
