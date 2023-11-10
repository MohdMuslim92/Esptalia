<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DoctorLocations extends Model
{
    use HasFactory;
    protected $guarded = [];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'medical_center_id',
        'clinic_id',
        'working_days',
        'working_hours',
        'availability',
    ];

    // Relationship to Doctors
    public function doctor()
    {
        return $this->belongsTo(Doctors::class);
    }

    // Relationship to Hospitals
    public function hospital()
    {
        return $this->belongsTo(Hospitals::class);
    }

    // Relationship to Medical centers
    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenters::class);
    }

    // Relationship to Clinics
    public function clinic()
    {
        return $this->belongsTo(Clinics::class);
    }
}
