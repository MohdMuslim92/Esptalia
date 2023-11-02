<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProviderController extends Controller
{
    public function show($userId)
    {
        // Retrieve the user based on the ID
        $user = User::with('provider.medicalCenter')->find($userId);

        if (!$user) {
            // Handle the case where the user is not found
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get the provider information through the user-provider relationship
        $provider = $user->provider;

        if (!$provider) {
            // Handle the case where the provider data is not found
            return response()->json(['error' => 'Provider not found'], 404);
        }

        return response()->json($provider);
    }
}
