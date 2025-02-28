<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
class Client extends Model
{
    protected $fillable = [
        'first_name',
        "last_name",
        "city_id",
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
