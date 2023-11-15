<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Appointments extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'appointment_time' => 'string',
    ];

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    // Fields of the appointments table to insert data into
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'provider_id',
        'appointment_time',
        'status',
    ];

    // Relationship to Patients
    public function patients()
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    // Relationship to Doctors
    public function doctors()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }
}
