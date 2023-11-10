<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Doctors extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'gender',
        'speciality',
        'doctor_pic',
    ];

    // Relationship to Location
    public function locations()
    {
        return $this->hasMany(DoctorLocations::class, 'doctor_id');
    }

    // Relationship to Appointments
    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'doctor_id');
    }

}
