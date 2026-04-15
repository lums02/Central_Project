<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'logo',
    ];
}
