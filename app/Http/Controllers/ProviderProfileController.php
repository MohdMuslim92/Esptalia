<?php

namespace App\Http\Controllers;

use App\Models\Providers;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProviderProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $userId = $request->user()->id;
        $provider = Providers::where('user_id', $userId)->first();
        if ($provider->type == 'hospital')
        {
            $providerType = 'hospital';
        } elseif ($provider->type == 'medical_center')
        {
            $providerType = 'medicalCenter';
        } elseif ($provider->type == 'clinic')
        {
            $providerType = 'clinic';
        }

        $validator = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone_number' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],

            // Provider fields
            'specialization' => ['string', 'max:255'],
            'type' => ['string', 'max:255'],

            // Medical Center fields
            'state' => ['string', 'max:255'],
            'city' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'working_days' => ['array', 'max:255'],
            'working_hours' => ['string', 'max:255'],
            'availability' => ['string', 'max:255'],
            'license_number' => ['string', 'max:255'],

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $userData = $validator->validated();

        // Convert working_days array to JSON string
        $userData['working_days'] = json_encode($request->input('working_days'));
        $request->user()->fill($userData);
        $request->user()->save();
        $request->user()->provider->fill($userData);
        $request->user()->provider->save();
        $request->user()->$providerType->fill($userData);
        $request->user()->$providerType->save();
                if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        return Redirect::route('provider-profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
