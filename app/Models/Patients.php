<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Patients extends Model
{
    use HasFactory;
    protected $guarded = [];

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Fields of the patients table to insert data into
    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'email',
    ];

    // Relationship to Appointments
    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'patient_id');
    }

    // Relationship to User
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }


}
