<?php

namespace App\Http\Middleware;

use Pecee\Http\Middleware\BaseCsrfVerifier;

class CsrfVerifier extends BaseCsrfVerifier
{
    protected $except = ['/request/*'];
}
