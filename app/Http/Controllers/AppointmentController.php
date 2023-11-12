<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Clinics;
use App\Models\Hospitals;
use App\Models\MedicalCenters;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Doctors;
use App\Models\Providers;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
class AppointmentController extends Controller
{
    public function index()
    {
        /* Function to display search results to book an appointment */

        $appointments = Appointments::orderBy('created_at', 'desc')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->select('appointments.*', 'patients.name as patient_name', 'patients.email as email',
                'patients.phone_number as phone_number', 'doctors.first_name as doctor_first_name',
                'doctors.last_name as doctor_last_name')
            ->get();

        return response()->json($appointments);
    }

    public function create(Request $request)
    {
        /* Function to create a new appointment */

        if (auth()->check()) {
            // User is logged in, retrieve the user ID
            $userId = auth()->user()->id;
            $request->merge(['user_id' => $userId]);
            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:255'],
                'email' => ['email', 'max:255'],
                'phone_number' => ['string', 'max:255'],
                'doctor_id' => ['string', 'max:100'],
                'provider_id' => ['string', 'max:100'],
                'appointment_time' => ['string', 'max:200'],
                'status' => ['string', 'max:200'],
                'user_id' => ['integer', 'exists:users,id'],
            ]);

        } else {
            // User is not logged in, validate data without user id
            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:255'],
                'email' => ['email', 'max:255'],
                'phone_number' => ['string', 'max:255'],
                'doctor_id' => ['string', 'max:100'],
                'provider_id' => ['string', 'max:100'],
                'appointment_time' => ['string', 'max:200'],
                'status' => ['string', 'max:200'],
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Format appointment_time to YYYY-MM-DD and wrap it in single quotes
        $formattedAppointmentTime = $request->appointment_time;

        // Create patient details and save it in the patients table
        $patient = Patients::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        // Create Appointment Details
        if ($patient) {
            // Save the appointment information to the appointments table
            Appointments::create([
                'patient_id' => $patient->id,
                'doctor_id' => $request->doctor_id,
                'provider_id' => $request->provider_id,
                'appointment_time' => "'".$formattedAppointmentTime."'",
                'status' => $request->status,
            ]);
        }
        /* This block is used to get the appointment details for sending confirmation
           email purpose, it should be commented as this unless you have configured
           your mail settings correctly

        $name = $request->name;
        $doctorName = 'Dr. ' . $request->doctor_name;
        $time = $request->appointment_time;
        $address = $request->address;
        $city = $request->city;
        $state = $request->state;
        $provider_name = $request->provider_name;
        $type = $request->type;

        Mail::to($request->email)->send(new AppointmentConfirmation($name, $doctorName, $time, $address, $city, $state, $provider_name, $type));
        */

        // Customize the success message
        $successMessage = 'Your appointment has been booked successfully. An email confirmation will be sent to your email address.';

        // Redirect to a success page
        return redirect()->route('booking-success')->with('success', $successMessage);
    }

    public function showAppointmentPage(Request $request)
    {
        /* Function to display appointment details in the
           appointment booking page */

        // Decode URL parameters
        $doctorId = base64_decode($request->query('doctorId'));
        $chosenDay = base64_decode($request->query('chosenDay'));
        foreach ($request->query() as $key => $value) {
            // Check if the key ends with '_id'
            if (str_ends_with($key, '_id')) {
                // Extract provider name from the key
                $providerName = $key;
                $providerId = base64_decode($value);
                break;
            }
        }

        // Fetch doctor data based on the $doctorId from your database
        $doctor = Doctors::find($doctorId);
        $provider = Providers::where($providerName, $providerId)->first();

        // Fetch users data based on user_id
        $user = User::find($provider->user_id)->first();

        // Fetch hospital, medical center or clinic data based on user_id
        if ($provider->type == 'hospital')
        {
            $healthcareProvider = Hospitals::where('user_id', $provider->user_id)->first();
        } elseif ($provider->type == 'medical_center')
        {
            $healthcareProvider = MedicalCenters::where('user_id', $provider->user_id)->first();
        } else
        {
            $healthcareProvider = Clinics::where('user_id', $provider->user_id)->first();
        }

        // Pass the doctor data, chosenDay, and providerId to the Appointment Inertia page
        return Inertia::render('Appointment', [
            'doctor' => $doctor,
            'appointment_time' => $chosenDay,
            'provider_type_id' => $providerId,
            'provider_id' => $provider,
            'user' => $user,
            'healthcareProvider' => $healthcareProvider,
        ]);
    }

    public function confirmAppointment(Appointments $appointment)
    {
        /* Function to confirm appointment by changing the status
           to confirmed in the appointments table */
        $appointment->status = 'confirmed';
        $appointment->save();

    }

    public function cancelAppointment(Appointments $appointment)
    {
        /* Function to cancel appointment by changing the status
           to canceled in the appointments table */
        $appointment->status = 'canceled';
        $appointment->save();

    }

    /*    public function bookAppointment(Request $request)
        {
            $doctorId = $request->query('doctorId');
            $chosenDay = $request->query('chosenDay');

            // Loop through all query parameters to find the correct provider ID and its name
            $providerId = null;
            $providerName = null;
            foreach ($request->query() as $key => $value) {
                // Check if the key ends with '_id'
                if (str_ends_with($key, '_id')) {
                    // Extract provider name from the key
                    $providerName = $key;
                    $providerId = $value;
                    break;
                }
            }

            // Retrieve doctor details based on the provided $doctorId from the database
            $doctor = Doctors::find($doctorId);

            // Pass the doctor details, chosenDay, and providerId to the view
            return view('appointments.book', compact('doctor', 'chosenDay', 'providerId', 'providerName'));
        } */

}
