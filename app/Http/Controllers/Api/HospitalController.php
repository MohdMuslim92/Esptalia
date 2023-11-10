<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Providers;
use Illuminate\Http\Request;
use App\Models\User;

class HospitalController extends Controller
{
    public function index()
    {
        // Fetch provider data from the database
        $providers = Providers::all();

        return response()->json($providers);
    }

    public function show($userId)
    {
        // Retrieve the user based on the ID
        $user = User::with('provider.hospital')->find($userId);

        if (!$user) {
            // Handle the case where the user is not found
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get the Hospital information through the provider-hospital relationship
        $hospital = $user->provider->hospital;

        if (!$hospital) {
            // Handle the case where the provider data is not found
            return response()->json(['error' => 'Provider not found'], 404);
        }

        return response()->json($hospital);
    }


    public function update(Request $request, $id)
    {
        // Validate and update provider data
        $provider = Providers::findOrFail($id);
        $request->validate([
        ]);

        // Update provider attributes based on the request data
        $provider->update($request->all());

        return response()->json($provider);
    }

    public function destroy($id)
    {
        // Delete a provider by ID
        $provider = Providers::findOrFail($id);
        $provider->delete();

        return response()->json(['message' => 'Provider deleted successfully']);
    }

}
