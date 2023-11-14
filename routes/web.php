<?php

use App\Http\Controllers\Api\ClinicController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\MedicalCenterController;
use App\Http\Controllers\Auth\RegisteredProvidersController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\AddDoctorController;
use App\Http\Controllers\HealthcareProviderController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\DoctorLocationsController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/booking', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return Inertia::render('index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('landing');

Route::get('/Healthcare-Provider', function () {
    // Healthcare provider dashboard
    return Inertia::render('HealthcareDashboard');
})->middleware(['auth', 'verified'])->name('healthcare.dashboard');

// Route for About page
Route::get('/About', function () {
    return Inertia::render('About', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('About');

Route::get('Contact', [AuthenticatedSessionController::class, 'create'])
    ->name('Contact');

// Route for Contact Us page
Route::get('/Contact-Us', function () {
    return Inertia::render('Contact', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('Contact');

// Add Doctor
Route::get('/Add-doctor', [AddDoctorController::class, 'create'])
    ->name('addDoctor');

// Update Doctor
Route::get('/Edit-doctor', [DoctorsController::class, 'update'])
    ->name('UpdateDoctor');

// Get Doctor
Route::get('/Get-doctor/{email}', [DoctorsController::class, 'index'])
    ->name('GetDoctor');

// Get Doctor location data
Route::get('/Get-locations/{doctorID}', [DoctorLocationsController::class, 'index'])
    ->name('GetDoctor');

// Edit Doctor
Route::get('/Edit-doctor/{email}', [DoctorsController::class, 'edit'])
    ->name('EditDoctor');

Route::patch('/update-doctor/{id}', [DoctorsController::class, 'update'])->name('doctor.update');

Route::post('/Add-doctor', [AddDoctorController::class, 'store']);

Route::get('/api/healthcare-providers/{id}', [HealthcareProviderController::class, 'show']);

Route::get('/api/providers/{userId}', [ProviderController::class, 'show']);

Route::get('/api/hospital/{userId}', [HospitalController::class, 'show']);

Route::get('/api/medical-center/{userId}', [MedicalCenterController::class, 'show']);

Route::get('/api/clinic/{userId}', [ClinicController::class, 'show']);

Route::get('/api/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

// Make an appointment
Route::post('/api/appointments', [AppointmentController::class, 'create'])
    ->name('MakeAppointment');

Route::get('/notifications', [NotificationController::class, 'index']);

Route::get('/api/doctors', function () {
    // Doctors list
    return Inertia::render('Doctors');
})->middleware(['auth', 'verified'])->name('doctors');

Route::get('/api/doctors/search', [DoctorsController::class, 'searchDoctors'])
    ->name('doctors.search');

Route::get('/api/doctors-list', [DoctorsController::class, 'show']);

Route::get('/api/doctor-locations/{providerId}', [DoctorLocationsController::class, 'show']);

Route::get('/book-appointment', [AppointmentController::class, 'showAppointmentPage']);

// Booked Appointments list
Route::get('/api/appointments/booked', function () {
    return Inertia::render('BookedAppointments');
})->middleware(['auth', 'verified'])->name('booked-appointments');

// Appointments book success
Route::get('/booking-success', function () {
    $successMessage = 'Your appointment has been placed. Once approved an email confirmation will be sent to your email address.';

    return Inertia::render('successBooking', [
        'successMessage' => $successMessage,
    ]);
})->name('booking-success');
// Confirm appointment
Route::patch('/api/appointments/confirm/{appointment}', [AppointmentController::class, 'confirmAppointment']);

// Cancel appointment
Route::patch('/api/appointments/cancel/{appointment}', [AppointmentController::class, 'cancelAppointment']);

// Get all appointments
Route::get('/api/appointments/display', [AppointmentController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/provider-profile', [ProviderProfileController::class, 'edit'])->name('provider-profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/provider-profile', [ProviderProfileController::class, 'update'])->name('provider-profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/api/register-user', [RegisteredUserController::class, 'create']);
Route::post('/api/register-provider', [RegisteredProvidersController::class, 'create']);

require __DIR__.'/auth.php';
