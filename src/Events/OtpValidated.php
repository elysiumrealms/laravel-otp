<?php

namespace Elysiumrealms\Otp\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * OtpValidated
 *
 * @package Elysiumrealms\Otp\Events
 */
class OtpValidated
{
    use Dispatchable, SerializesModels;

    /**
     * @var string
     */
    public $identifier;

    /**
     * Constructor
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }
}
