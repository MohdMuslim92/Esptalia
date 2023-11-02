<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getUserData($id)
    {
        try {
            \Log::info('Fetching user data for ID: ' . $id);

            $user = User::with('provider.hospital')->findOrFail($id);

            \Log::info('User found: ' . json_encode($user));

            return response()->json([
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user data: ' . $e->getMessage());

            return response()->json([
                'error' => 'User not found.',
            ], 404);
        }
    }
}
