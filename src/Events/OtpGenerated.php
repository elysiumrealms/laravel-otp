<?php

namespace Elysiumrealms\Otp\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * OtpGenerated
 *
 * @package Elysiumrealms\Otp\Events
 */
class OtpGenerated
{
    use Dispatchable, SerializesModels;

    public $via;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $token;

    /**
     * Constructor
     */
    public function __construct($identifier, $via, $token)
    {
        $this->via = $via;
        $this->token = $token;
        $this->identifier = $identifier;
    }
}
