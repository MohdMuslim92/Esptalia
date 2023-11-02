<?php

namespace App\Http\Controllers;

use App\Models\HealthcareProvider;
class HealthcareProviderController extends Controller
{
    public function show($id)
    {
        $healthcareProvider = HealthcareProvider::find($id);

        if (!$healthcareProvider) {
            return response()->json(['error' => 'Healthcare provider not found'], 404);
        }

        return response()->json($healthcareProvider);
    }

}
