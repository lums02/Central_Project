<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * Les noms des attributs qui ne doivent pas être tronqués.
     *
     * @var array
     */
    protected $except = [
        'mot_de_passe',
        'mot_de_passe_confirmation',
    ];
}
