<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Center;
use Modules\Accounting\Models\Currency;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Safe;
use Modules\Accounting\Models\Voucher;
use Modules\Accounting\Models\AccountEntry;
use Modules\Accounting\Models\Cheque;
use Modules\Accounting\Models\Entry;
use Modules\Accounting\Models\Transfer;
use Modules\Accounting\Models\Year;
// use Illuminate\Support\Arr;
class AccountsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        // Model::unguard(); 'name', 'type', 'number', 'side', 'main_account','final_account'
        $this->truncateAccountingTables();
        foreach (config('accounting.seeder.accounts') as $account) {
            $this->createAccount($account);
        }
    }
    
    public function createAccount($attributes)
    {
        $this->command->info('Creating "' . $attributes["name"] . '" account ...');
        $account = Account::firstOrCreate(array_except($attributes, 'accounts'));
        if(array_key_exists('accounts', $attributes)){
            foreach ($attributes['accounts'] as $child) {
                $child['main_account'] = $account->id;
                $this->createAccount($child);
            }
        }
    }
    
    public function truncateAccountingTables()
    {
        Schema::disableForeignKeyConstraints();
        if(config('accounting.seeder.truncate_tables')) {

            $this->command->info('Truncating Account Model ...');
            Account::truncate();

            // $this->command->info('Truncating AccountYear Model ...');
            // AccountYear::truncate();

            $this->command->info('Truncating Center Model ...');
            Center::truncate();

            // $this->command->info('Truncating Currency Model ...');
            // Currency::truncate();

            $this->command->info('Truncating Expense Model ...');
            Expense::truncate();

            $this->command->info('Truncating Safe Model ...');
            Safe::truncate();

            $this->command->info('Truncating Voucher Model ...');
            Voucher::truncate();

            $this->command->info('Truncating AccountEntry Model ...');
            AccountEntry::truncate();

            // $this->command->info('Truncating Cheque Model ...');
            // Cheque::truncate();

            $this->command->info('Truncating Entry Model ...');
            Entry::truncate();

            $this->command->info('Truncating Transfer Model ...');
            Transfer::truncate();

            $this->command->info('Truncating Year Model ...');
            Year::truncate();

        }
        Schema::enableForeignKeyConstraints();
    }
}