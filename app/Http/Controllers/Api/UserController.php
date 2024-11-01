<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
            'role' => $request->role,
        ]);

        return response()->json(new UserResource($user), 201);
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Mencari pengguna berdasarkan email
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404); // Logika jika pengguna tidak ditemukan
    }

    // Cek jika password cocok
    if (Hash::check($request->password, $user->password)) {
        // Membuat token untuk pengguna
        $token = $user->createToken('MyAppToken')->plainTextToken;

        // Mengembalikan token dan data pengguna sebagai respons
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 200);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'string|max:150',
            'email' => 'string|email|max:100|unique:users,email,' . $user->iduser,
            'role' => 'in:admin,user',
        ]);

        $user->update($request->all());

        return response()->json(new UserResource($user), 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
