<?php

namespace Elysiumrealms\Otp\Console\Commands;

use Illuminate\Console\Command;

class CleanOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Otp database, remove all old otps that is expired or used.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var \Elysiumrealms\Otp\Models\Otp */
        $class = config('otp.model');

        try {
            $otps = $class::where('valid', 0)
                ->where('updated_at', '<', now()->subMinutes(5))
                ->delete();

            $this->info("Found {$otps} expired otps.");
            $this->info($otps ? "Expired tokens deleted" : "No tokens were deleted");
        } catch (\Exception $e) {
            $this->error("Error:: {$e->getMessage()}");
        }

        return 0;
    }
}
