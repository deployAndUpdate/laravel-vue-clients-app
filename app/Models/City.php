<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

class City extends Model
{

    public function clients( )
    {
        return $this->hasMany(Client::class);
    }
}
