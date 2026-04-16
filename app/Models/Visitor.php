<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $guarded = [];

    protected $casts = [
        'face_data' => 'array',
    ];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
