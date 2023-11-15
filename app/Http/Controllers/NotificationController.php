<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Function to retrieve all the un confirmed appointments as notifications
        $userRole = Auth::user()->role;
        $notifications = Appointments::where('status', 'booked')
            ->orderBy('created_at', 'desc')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->select('appointments.*', 'patients.name as patient_name', 'patients.email as email',
                'patients.phone_number as phone_number', 'doctors.first_name as doctor_first_name',
                'doctors.last_name as doctor_last_name')
            ->get();

        return response()->json([
            'allNotifications' => $notifications,
            'user_role' => $userRole
        ]);
    }

}
