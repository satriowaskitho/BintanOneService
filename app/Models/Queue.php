<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_email_sent_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
