<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'service_type_id',
        'date',
        'time',
        'purpose',
        'required_documents',
        'email',
        'status',
        'token',
        'checked_in_at',
        'last_email_sent_at',
    ];

    protected $casts = [
        'date' => 'date',
        'checked_in_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
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
