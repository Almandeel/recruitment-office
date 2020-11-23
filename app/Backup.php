<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Models\{Year, Safe, Account, Entry, Voucher, Center, Expense};
class Backup extends BaseModel
{
    protected $fillable = ['models', 'user_id'];
    protected $backupables = [
        'User' => User::class, 
        'Role' => Role::class, 
        'Log' => Log::class, 
        'Attachment' => Attachment::class, 
        'Year' => Year::class, 
        'Safe' => Safe::class, 
        'Account' => Account::class, 
        'Entry' => Entry::class, 
        'Voucher' => Voucher::class, 
        'Center' => Center::class, 
        'Expense' => Expense::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function setModels(array $models){
        $this->models = implode(',', $models);
    }
    
    public function getModels(){
        return explode(',', $this->models);
    }
    public function getModelName($model){
        return __('backups.models' . getModel($model)->getTable());
    }
    public function getModel($model, $full_path = false){
        if($full_path){
            return $this->backupables[$model];
        }
        return (new $model);
    }

    public function generateFileName(){
        return 'backup-' . date('Ymdhis') .rand();
    }
    
}