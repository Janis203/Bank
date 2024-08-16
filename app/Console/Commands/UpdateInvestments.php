<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateInvestments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investments:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update investments daily';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $investments = Investment::all();

        foreach ($investments as $investment) {
            $daysInvested = Carbon::now()->diffInDays($investment->invested_at);
            $growthFactor = pow(1.10, $daysInvested);
            $investment->amount *= $growthFactor;
            $investment->save();
        }

        $this->info('Investments updated successfully.');
    }
}
