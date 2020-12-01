<?php

namespace STS\SnapThis\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use STS\SnapThis\Client;

/**
 * @see \STS\SnapThis\Client
 */
class SnapThis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }

    public static function setDefaults(Closure $closure)
    {
        Client::setDefaults($closure);
    }
}
