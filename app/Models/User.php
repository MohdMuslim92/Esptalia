<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $guarded = [];
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'role',
        'gender',
        'date_of_birth',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship to Providers
    public function provider()
    {
        return $this->hasOne(Providers::class, 'user_id', 'id');
    }

    // Relationship to Hospitals
    public function hospital()
    {
        return $this->hasOne(Hospitals::class, 'user_id', 'id');
    }

    // Relationship to Medical centers
    public function medicalCenter()
    {
        return $this->hasOne(MedicalCenters::class, 'user_id', 'id');
    }

    // Relationship to Clinics
    public function clinic()
    {
        return $this->hasOne(Clinics::class, 'user_id', 'id');
    }

    // Relationship to Patientss
    public function patients()
    {
        return $this->belongsTo(Patients::class);
    }


}
