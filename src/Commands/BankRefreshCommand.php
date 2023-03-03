<?php

namespace Dipantry\Rupiah\Commands;

use Dipantry\Rupiah\Models\Bank;
use Dipantry\Rupiah\Service\BankService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BankRefreshCommand extends Command
{
    private BankService $bankService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rupiah:bank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh bank list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->bankService = new BankService();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Refreshing bank list...');
        Schema::disableForeignKeyConstraints();
        $this->reset();

        $banks = $this->bankService->getBankList();
        foreach ($banks as $bank) {
            preg_match_all("/\((.*?)\)/", $bank['name'], $matches);
            $insideBrackets = $matches[1] ?? null;

            preg_match_all("/\b\w+\b(?![^()]*[)])/", $bank['name'], $matches);
            $outsideBrackets = $matches[0];

            $bk = new Bank();
            $bk->name = ucwords(strtolower(implode(' ', $outsideBrackets)));
            $bk->alt_name = $insideBrackets ? ucwords(strtolower(implode(' ', $insideBrackets))) : null;
            $bk->code = $bank['code'];
            $bk->save();
        }

        Schema::enableForeignKeyConstraints();
        $this->info('Bank list refreshed');
    }

    /**
     * Reset bank list on database.
     */
    private function reset()
    {
        DB::table(config('rupiah.table_prefix').'banks')->truncate();
    }
}
