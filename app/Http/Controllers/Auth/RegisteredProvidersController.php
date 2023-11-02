<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Providers;
use App\Models\Hospitals;
use App\Models\MedicalCenters;
use App\Models\Clinics;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredProvidersController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'role' => 'required|string|max:20',
            'description' => 'required|string|max:500',
            'working_days' => 'required|string|max:50',
            'working_hours' => 'required|string|max:50',
            'availability' => 'required|string|max:1',
            'type' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'license_pic' => 'required|image',
        ]);

        try {
            \DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'address' => $request->address,
            ]);

            $provider = null;
            if ($request->type == 'hospital') {
                $provider = Hospitals::create([
                    'user_id' => $user->id,
                    'state' => $request->state,
                    'city' => $request->city,
                    'description' => $request->description,
                    'working_days' => $request->working_days,
                    'working_hours' => $request->working_hours,
                    'availability' => $request->availability,
                    'license_number' => $request->license_number,
                ]);
            } elseif ($request->type == 'medical_center') {
                $provider = MedicalCenters::create([
                    'user_id' => $user->id,
                    'state' => $request->state,
                    'city' => $request->city,
                    'description' => $request->description,
                    'working_days' => $request->working_days,
                    'working_hours' => $request->working_hours,
                    'availability' => $request->availability,
                    'license_number' => $request->license_number,
                ]);
            } elseif ($request->type == 'clinic') {
                $provider = Clinics::create([
                    'user_id' => $user->id,
                    'state' => $request->state,
                    'city' => $request->city,
                    'description' => $request->description,
                    'working_days' => $request->working_days,
                    'working_hours' => $request->working_hours,
                    'availability' => $request->availability,
                    'license_number' => $request->license_number,
                ]);
            }

            // Create Provider Details
            if ($provider) {
                if ($request->type == 'hospital') {
                    Providers::create([
                        'user_id' => $user->id,
                        'type' => $request->type,
                        'hospital_id' => $provider->id,
                    'specialization' => $request->specialization,
                        'license_pic' => $request->file('license_pic')->store('path_to_store_images'),
                    ]);
                    } elseif ($request->type == 'medical_center') {
                    Providers::create([
                        'user_id' => $user->id,
                        'type' => $request->type,
                        'medical_center_id' => $provider->id,
                    'specialization' => $request->specialization,
                        'license_pic' => $request->file('license_pic')->store('path_to_store_images'),
                    ]);
                } else {
                    Providers::create([
                        'user_id' => $user->id,
                        'type' => $request->type,
                        'clinic_id' => $provider->id,
                    'specialization' => $request->specialization,
                        'license_pic' => $request->file('license_pic')->store('path_to_store_images'),
                    ]);
                }
            }

            \DB::commit();
            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            \DB::rollback();
            // Handle exception (log, return response, etc.)
            dd('Error occurred: ' . $e->getMessage());
        }
    }
}
