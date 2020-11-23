<?php
namespace Modules\Accounting\Traits;
/**
 *  Account Trait
 */
trait AccountTrait
{
    public $DEFAULT_ACCOUNTS = [1, 11, 12, 121, 2, 3, 4, 5];

    public function balance(){
        
    }

    public function arrange(){
        foreach ($this->accounts as $index => $account) {
            $account->update(['number' => $this->number + ($index+1)]);
        }

        foreach ($this->accounts as $account) {
            $account->arrange();
        }
    }

    public function isDefault(){
        return in_array($this->id, $DEFAULT_ACCOUNTS);
    }
    
}
