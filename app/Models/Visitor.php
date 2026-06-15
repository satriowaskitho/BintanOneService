<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $guarded = [];

    protected $casts = [
        'face_data' => 'encrypted:array',
    ];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
