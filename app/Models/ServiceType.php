<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $guarded = [];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
