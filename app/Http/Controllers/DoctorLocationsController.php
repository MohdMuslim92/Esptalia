<?php

namespace App\Http\Controllers;

use App\Models\Providers;
use App\Models\DoctorLocations;

class DoctorLocationsController extends Controller
{
    public function index($doctorID)
    {
        $userId = auth()->user()->id;
        // Retrieve provider type based on the user ID
        $provider = Providers::where('user_id', $userId)->first();
        if ($provider->type == 'hospital')
        {
            $providerType = 'hospital_id';
        } elseif ($provider->type == 'medical_center')
        {
            $providerType = 'medical_center_id';
        } elseif ($provider->type == 'clinic')
        {
            $providerType = 'clinic_id';
        }

        if (!$provider) {
            // Handle the case where the provider data is not found
            return response()->json(['error' => 'Provider not found'], 404);
        }
        $doctorLocations = DoctorLocations::where('doctor_id', $doctorID)->where($providerType, $userId)->first();

        if (!$doctorLocations) {
            // Handle the case where the doctor locations data is not found
            return response()->json(['error' => 'Doctor locations not found'], 404);
        }

        // Return the doctor locations data as JSON response
        return response()->json($doctorLocations);
    }

    public function show($userId)
    {
        // Retrieve provider type based on the user ID
        $provider = Providers::where('user_id', $userId)->first();
        if ($provider->type == 'hospital')
        {
            $providerType = 'hospital_id';
        } elseif ($provider->type == 'medical_center')
        {
            $providerType = 'medical_center_id';
        } elseif ($provider->type == 'clinic')
        {
            $providerType = 'clinic_id';
        }
        $doctorLocations = DoctorLocations::where($providerType, $userId)->get();

        if (!$doctorLocations) {
            // Handle the case where the doctor locations data is not found
            return response()->json(['error' => 'Doctor locations not found'], 404);
        }

        // Return the doctor locations data as JSON response
        return response()->json($doctorLocations);
    }

}
