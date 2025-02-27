<?php

namespace Elysiumrealms\Otp\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class ValidateOtp extends Command
{
    /**
     * The signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:validate
        {identifier : The identifier to validate the OTP for}
        {--token= : The OTP token to validate}
        {--force : Force the OTP token to be validated}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Validate an OTP token for a given identifier';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $modelClass = config('otp.database.model');

        $identifier = $this->argument('identifier');

        /** @var \Elysiumrealms\Otp\Models\Otp */
        $otp = $modelClass::where('identifier', $identifier)
            ->when($this->option('token'), function ($query, $token) {
                return $query->where('token', $token);
            })
            ->first();

        if (!$otp) {
            $this->error('Invalid OTP token');
            return;
        }

        if (!$this->option('force')) {
            if (!$otp->valid) {
                $this->error('OTP token already used');
                return;
            }

            $now = Carbon::now();
            $validity = $otp->created_at->addMinutes($otp->validity);

            if (strtotime($validity) < strtotime($now)) {
                $this->error('OTP token expired');
                return;
            }
        }

        $otp->valid = false;
        $otp->status = true;
        $otp->save();

        $this->info('OTP token validated successfully');
    }
}
