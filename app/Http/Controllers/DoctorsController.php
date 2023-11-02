<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Doctors;
use App\Models\DoctorLocations;
use App\Models\Providers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DoctorsController extends Controller
{
    public function index($email)
    {
        $doctor = Doctors::where('email', $email)->first();

        return response()->json($doctor);
    }

    public function show()
    {
        $doctors = Doctors::all();

        return response()->json($doctors);
    }

    public function edit($email): Response
    {
        // Find the doctor based on the passed email
        $doctor = Doctors::where('email', $email)->first();

        // Check if the doctor is found
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Passing the doctor data to the Inertia template
        return Inertia::render('EditDoctor', ['doctor' => $doctor]);

    }

    public function update(Request $request, $id)
    {
        // Get the userId
        $userId = auth()->user()->id;

        // Find the doctor based on the passed email
        $doctor = Doctors::where('id', $id)->first();

        // Check if the doctor is found
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Retrieve provider type based on the user ID
        $provider = Providers::where('user_id', $userId)->first();
        if ($provider->type == 'hospital')
        {
            $providerType = 'hospital_id';
            $providerName = 'hospital';
        } elseif ($provider->type == 'medical_center')
        {
            $providerType = 'medical_center_id';
            $providerName = 'medicalCenter';
        } elseif ($provider->type == 'clinic')
        {
            $providerType = 'clinic_id';
            $providerName = 'clinic';
        }

        // Retrieve additional data from DoctorLocations table based on doctor id and current user id
        $doctorLocationData = DoctorLocations::where('doctor_id', $doctor->id)
            ->where($providerType, $userId)
            ->first();


        $validator = Validator::make($request->all(), [
            // Doctor fields
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone_number' => ['string', 'max:255'],
            'gender' => ['string', 'max:1'],
            'speciality' => ['string', 'max:100'],
//            'doctor_pic' => ['image', 'max:2048'],

            // Doctor locations fields
            'working_days' => ['array', 'max:500'],
            'working_hours' => ['string', 'max:255'],
            'availability' => ['string', 'max:1'],

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $userData = $validator->validated();


        // Convert working_days array to JSON string
        $userData['working_days'] = json_encode($request->input('working_days'));
//        dd($userData['working_days']);
        $request->user()->$providerName->doctorLocation->fill($userData);
        $request->user()->$providerName->doctorLocation->save();
        $request->user()->$providerName->doctorLocation->doctor->fill($userData);
        $request->user()->$providerName->doctorLocation->doctor->save();
        }

    public function searchDoctors(Request $request)
    {
        try {
            $specialization = $request->input('specialization');
            $name = $request->input('name');
            $state = $request->input('state');
            $city = $request->input('city');

            // Initialize a query to retrieve doctors
            $query = Doctors::query();

            // Apply specialization filter if provided
            if ($specialization) {
                $query->where('speciality', $specialization);
            }

            // Apply name filter if provided
            if ($name) {
                // Split the provided name into first name and last name
                $nameParts = explode(' ', $name);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                // Apply filter based on first_name and last_name
                $query->where(function ($query) use ($firstName, $lastName) {
                    $query->where('first_name', 'like', '%' . $firstName . '%')
                        ->orWhere('last_name', 'like', '%' . $lastName . '%');
                });
            }

            // Apply state and city filters if provided
            if ($state && $city) {
                $query->whereHas('locations', function ($query) use ($state, $city) {
                    // ... (your existing location filters)
                });
            } elseif ($state) {
                $query->whereHas('locations', function ($query) use ($state) {
                    // ... (your existing location filters)
                });
            } elseif ($city) {
                $query->whereHas('locations', function ($query) use ($city) {
                });
            }

            // Retrieve the list of doctors based on the search criteria
            $doctorsList = $query->with('locations')->get();

            // Modify the response data to include hospital_id, medical_center_id, or clinic_id
            $modifiedDoctorsList = $doctorsList->map(function ($doctor) {
                $locations = $doctor->locations->first();

                // Include the associated IDs in the response
                return [
                    'id' => $doctor->id,
                    'first_name' => $doctor->first_name,
                    'last_name' => $doctor->last_name,
                    'speciality' => $doctor->speciality,
                    'city' => $locations->city,
                    'state' => $locations->state,
                    'hospital_id' => $locations->hospital_id,
                    'medical_center_id' => $locations->medical_center_id,
                    'clinic_id' => $locations->clinic_id,
                ];
            });

            // Return the modified list of doctors based on the search criteria
            return response()->json($modifiedDoctorsList);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error searching doctors: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

}
