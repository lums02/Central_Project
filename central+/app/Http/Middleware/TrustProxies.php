<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Les proxies de confiance.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * Les en-têtes qui doivent être utilisés pour détecter les proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
