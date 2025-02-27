<?php

namespace Elysiumrealms\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Elysiumrealms\Otp\OtpService;
use Elysiumrealms\Otp\Events\OtpGenerated;
use Elysiumrealms\Otp\Events\OtpValidated;

class OtpController
{
    /**
     * @var OtpService
     */
    protected $service;

    /**
     * Constructor
     */
    public function __construct(OtpService $service)
    {
        $this->service = $service;
    }

    /**
     * Generate a new OTP
     *
     * @param Request $request
     * @param string $via
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request, $via)
    {
        $request->validate([
            'identifier' => implode('|', [
                'required',
                config('otp.validations')[$via],
            ]),
        ]);

        $response = $this->service->generate(
            $request->input('identifier'),
            $via
        );

        if ($response->status)
            event(new OtpGenerated(
                $request->input('identifier'),
                $via,
                $response->token
            ));

        if (config('otp.debug') === false)
            unset($response->token);

        return response()->json($response);
    }

    /**
     * Validate an OTP
     *
     * @param Request $request
     * @param string $via
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request, $via)
    {
        $request->validate([
            'identifier' => implode('|', [
                'required',
                config('otp.validations')[$via],
            ]),
            'token' => 'required|string',
        ]);

        $response = $this->service->validate(
            $request->input('identifier'),
            $request->input('token')
        );

        if ($response->status)
            event(new OtpValidated(
                $request->input('identifier')
            ));

        return response()->json($response);
    }
}
