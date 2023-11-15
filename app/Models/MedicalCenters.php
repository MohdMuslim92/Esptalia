<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MedicalCenters extends Authenticatable
{
    protected $guarded = [];

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Fields of the Medical centers table to insert data into
    protected $fillable = [
        'user_id',
        'state',
        'city',
        'description',
        'working_days',
        'working_hours',
        'availability',
        'license_number',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Providers
    public function provider()
    {
        return $this->hasOne(Providers::class, 'medical_center_id');
    }

    public function doctorLocation()
    {
        return $this->hasOne(DoctorLocations::class, 'medical_center_id');
    }

}
