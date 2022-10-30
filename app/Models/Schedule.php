<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'time',
        'slots',
        'used_slots',
        'active'
    ];

    /**
     * Returns the appointments of a schedule
     */
    public function appointments()
    {
        // return $this->hasMany(Appointment::class);
        return null;
    }

    /**
     * Returns the valid and uncompleted appointments of a schedule
     */
    public function validAppointments() 
    {
        // return $this->hasMany(Appointment::class)->where('done', false)->where('active',true);
        return null;
    }

    /**
     * Returns the valid and uncompleted appointments of a schedule
     */
    public function inactiveAppointments() 
    {
        // return $this->hasMany(Appointment::class)->where('done', true)->where('active',false);
        //@TODO: check to see if this is right (maybe done=true and active = true?)
        return null;
    }
}
