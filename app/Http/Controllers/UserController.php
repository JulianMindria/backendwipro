<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'superior_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'superior_id' => $request->superior_id,
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $request->validate([
            'username' => 'string|unique:users,username,' . $id,
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'superior_id' => 'nullable|exists:users,id',
        ]);

        $user->update([
            'username' => $request->username ?? $user->username,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'superior_id' => $request->superior_id ?? $user->superior_id,
        ]);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }
}
