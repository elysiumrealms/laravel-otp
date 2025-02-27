<?php

namespace Elysiumrealms\Otp\Facades;

use Elysiumrealms\Otp\OtpService;
use Illuminate\Support\Facades\Facade;

/**
 * Otp
 *
 * @method static \Elysiumrealms\Otp\Models\Otp verified(string $identifier)
 *
 * @package Elysiumrealms\Otp\Facades
 */
class Otp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OtpService::class;
    }
}
