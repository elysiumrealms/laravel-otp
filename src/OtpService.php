<?php

namespace Elysiumrealms\Otp;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Lang;

class OtpService
{
    /**
     * The app instance
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The model class
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Constructor
     */
    public function __construct($app)
    {
        $this->app = $app;

        $this->modelClass = $app->config->get('otp.database.model');
    }

    /**
     * @param string $identifier
     * @param string $type
     * @param int $length
     * @param int $validity
     * @return object
     * @throws Exception
     */
    public function generate(string $identifier, string $via): object
    {
        /** @var \Elysiumrealms\Otp\Models\Otp */
        $this->modelClass::where('identifier', $identifier)
            ->where('valid', true)->delete();

        switch ($type = config('otp.type')) {
            case "numeric":
                $token = $this->generateNumericToken(config('otp.length'));
                break;
            case "alpha_numeric":
                $token = $this->generateAlphanumericToken(config('otp.length'));
                break;
            default:
                throw new Exception("{$type} is not a supported type");
        }

        $this->modelClass::create([
            'via' => $via,
            'token' => $token,
            'identifier' => $identifier,
            'validity' => config('otp.validity')
        ])->notify(new Notifications\OtpNotification());

        return (object)[
            'status' => true,
            'token' => $token,
            'message' => Lang::get('otp::messages.generated')
        ];
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */
    public function validate(string $identifier, string $token): object
    {
        /** @var \Elysiumrealms\Otp\Models\Otp */
        $otp = $this->modelClass::where('identifier', $identifier)
            ->where('token', $token)->first();

        if (!is_null($otp)) {
            if ($otp->valid) {
                $now = Carbon::now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                $otp->update(['valid' => false]);

                if (strtotime($validity) < strtotime($now)) {
                    return (object)[
                        'status' => false,
                        'message' => Lang::get('otp::messages.expired')
                    ];
                }

                $otp->update(['valid' => false, 'status' => true]);

                return (object)[
                    'status' => true,
                    'message' => Lang::get('otp::messages.valid')
                ];
            }

            $otp->update(['valid' => false]);

            return (object)[
                'status' => false,
                'message' => Lang::get('otp::messages.not_valid')
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => Lang::get('otp::messages.not_exist')
            ];
        }
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public function verified(string $identifier)
    {
        /** @var \Elysiumrealms\Otp\Models\Otp */
        $otp = $this->modelClass::where('identifier', $identifier)
            ->where('status', 1)->first();

        return !is_null($otp);
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    private function generateNumericToken(int $length = 4): string
    {
        $i = 0;
        $token = "";

        while ($i < $length) {
            $token .= random_int(0, 9);
            $i++;
        }

        return $token;
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateAlphanumericToken(int $length = 4): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($characters), 0, $length);
    }
}
