<?php

namespace App\Http\Controllers;

use App\Models\Appointments;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Appointments::where('status', 'booked')
            ->orderBy('created_at', 'desc')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->select('appointments.*', 'patients.name as patient_name', 'patients.email as email',
                'patients.phone_number as phone_number', 'doctors.first_name as doctor_first_name',
                'doctors.last_name as doctor_last_name')
            ->get();

        return response()->json($notifications);
    }

/*    public function show()
    {
        $notifications = Appointments::where('status', 'booked')
            ->orderBy('created_at', 'desc')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->select('appointments.*', 'patients.name as patient_name', 'patients.email as email',
            'patients.phone_number as phone_number', 'doctors.first_name as doctor_first_name',
            'doctors.last_name as doctor_last_name')
            ->get();

        return response()->json($notifications);

        // Pass the Appointments details view
        return view('appointments.book', compact('notifications'));
    } */
}
