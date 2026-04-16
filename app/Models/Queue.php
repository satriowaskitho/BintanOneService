<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $guarded = [];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
