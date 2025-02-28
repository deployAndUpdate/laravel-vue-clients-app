<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'type',
        "value",
        "client_id",
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
