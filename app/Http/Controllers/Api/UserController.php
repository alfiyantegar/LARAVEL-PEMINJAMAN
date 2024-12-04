<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

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
            'password' => $request->password, // Tidak menggunakan Hash::make
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

        $user = User::where('email', $request->email)->first();

        // Tidak menggunakan Hash::check
        if (!$user || $user->password !== $request->password) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Tidak menggunakan token autentikasi
        return response()->json([
            'message' => 'Login berhasil',
            'user' => new UserResource($user),
            'role' => $user->role,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Tidak menggunakan token, logout tidak diperlukan
        return response()->json(['message' => 'Logout berhasil'], 200);
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

        $user->update($request->only(['name', 'email', 'role']));

        return response()->json(new UserResource($user), 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * Mengambil data pengguna.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
{
    // Mengambil ID pengguna dari parameter body request
    $userId = $request->input('user_id');  // Ambil user_id dari body request

    // Mencari pengguna berdasarkan ID
    $user = User::find($userId);

    if ($user) {
        return response()->json(['iduser' => $user->iduser], 200);  // Mengirimkan ID pengguna dalam response JSON
    }

    return response()->json(['message' => 'User not found'], 404);
}


}

