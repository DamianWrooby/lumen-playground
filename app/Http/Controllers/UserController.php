<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function store(Request $request) {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->das = $request->das;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = $request->role;

            if ($user->save()) {
                return response()->json(['status' => 'success', 'message' => 'User created succesfully']);
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $das) {
        try {
            $user = User::where('das', $das)->firstOrFail();
            $user->name = $request->name;
            $user->das = $request->das;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = $request->role;

            if ($user->save()) {
                return response()->json(['status' => 'success', 'message' => 'User updated succesfully']);
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function show($das) {
        $user = User::where('das', $das)->firstOrFail();

        return $user;
    }

    public function destroy($das) {
        try {
            $user = User::where('das', $das)->firstOrFail();

            if ($user->delete()) {
                return response()->json(['status' => 'success', 'message' => 'User removed succesfully']);
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
