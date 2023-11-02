<?php

namespace App\Http\Controllers;

use App\Models\DoctorLocations;
use App\Models\Doctors;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AddDoctorController extends Controller
{
    /**
     * Display Add doctor view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('AddDoctor');
    }

    /**
     * Handle an incoming Add doctor request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Get the current authenticated user's ID
        $user = Auth::user();
        $providerType = $user->provider->type;

        if ($providerType == 'hospital')
        {
            $provider = 'hospital_id';
        } elseif ($providerType == 'medical_center')
        {
            $provider = 'medical_center_id';
        } elseif ($providerType == 'clinic')
        {
            $provider = 'clinic_id';
        }

        $request->validate([
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone_number' => 'required|string',
                'email' => 'required|email',
                'gender' => 'required|string|in:M,F',
                'speciality' => 'required|string|in:general,eye,ent',
                'working_days' => 'required|string',
                'working_hours' => 'required|string',
                'availability' => 'required|string|in:Y,N',
                'doctor_pic' => 'required|image|max:2048',
            ])
        ]);

        $validatedData[$provider] = 'required|string';

        $doctor = Doctors::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'speciality' => $request->speciality,
        ]);

        // Create Doctor location Details
        if ($doctor) {
            // Save the doctor's location information to the doctor_location table
            DoctorLocations::create([
                'doctor_id' => $doctor->id,
                $provider=> $user->id,
                'working_days' => $request->working_days,
                'working_hours' => $request->working_hours,
                'availability' => $request->availability,
            ]);
        }

        // Handle file upload (image is storing in a public directory)
        $file = $request->file('doctor_pic');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('doctor_images'), $fileName);

        // Update the doctor's profile picture field in the doctors table
        $doctor->update(['doctor_pic' => $fileName]);

        // Redirect to a success page
        return redirect()->route('healthcare.dashboard')->with('success', 'Doctor added successfully!');        }
    }
