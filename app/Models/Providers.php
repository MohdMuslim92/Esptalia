<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Providers extends Authenticatable
{
    protected $guarded = [];

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Fields of the Providers table to insert data into
    protected $fillable = [
        'user_id',
        'hospital_id',
        'medical_center_id',
        'clinic_id',
        'type',
        'specialization',
        'license_pic',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Hospitals
    public function hospital()
    {
        return $this->belongsTo(Hospitals::class, 'hospital_id');
    }

    // Relationship to Medical centers
    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenters::class, 'medical_center_id');
    }

    // Relationship to Clinics
    public function clinic()
    {
        return $this->belongsTo(Clinics::class, 'clinic_id');
    }

}
