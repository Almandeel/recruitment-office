<?php
if (!function_exists('account')) {
    
    function account($id = null)
    {
        if($id == null){
            $account = request()->route()->parameters['account'];
            if ($account instanceof App\Account) {
                return $account;
            }else{
                return \Modules\Accounting\Models\Account::find($account);
            }
        }
        return \Modules\Accounting\Models\Account::find($id);
    }
}

if (!function_exists('secondaryAccounts')) {
    
    function secondaryAccounts()
    {
        return \Modules\Accounting\Models\Account::secondaryAccounts();
    }
}

if (!function_exists('currencies')) {
    
    function currencies()
    {
        return \Modules\Accounting\Models\Currency::all();
    }
}

if (!function_exists('format_date')) {
    
    function format_date($date, $format = 'Y/m/d')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('year')) {
    
    function year()
    {
        // return \Modules\Accounting\Models\Year::where('active', 1)->orderBy('updated_at', 'DESC')->limit(1)->get()->last();
        return request()->session()->get('year');
    }
}

if (!function_exists('yearId')) {
    
    function yearId()
    {
        $year = year();
        if(!is_null($year)){
            return $year->id;
        }
        return null;
    }
}

if (!function_exists('roots')) {
    
    function roots($all = false)
    {
        return \Modules\Accounting\Models\Account::roots($all);
    }
}

if (!function_exists('assetsAccount')) {
    
    function assetsAccount()
    {
        return \Modules\Accounting\Models\Account::assets();
    }
}

if (!function_exists('currentAssetsAccount')) {
    
    function currentAssetsAccount()
    {
        return \Modules\Accounting\Models\Account::currentAssets();
    }
}

if (!function_exists('cashAccount')) {
    
    function cashAccount()
    {
        return \Modules\Accounting\Models\Account::cashes();
    }
}

if (!function_exists('safesAccount')) {
    
    function safesAccount()
    {
        return \Modules\Accounting\Models\Account::safes();
    }
}

if (!function_exists('bankAccount')) {
    
    function bankAccount()
    {
        return \Modules\Accounting\Models\Account::banks();
    }
}

if (!function_exists('finalAccount')) {
    
    function finalAccount()
    {
        return \Modules\Accounting\Models\Account::finals();
    }
}

if (!function_exists('expensesAccount')) {
    
    function expensesAccount()
    {
        return \Modules\Accounting\Models\Account::expenses();
    }
}

if (!function_exists('expenseAccount')) {
    
    function expenseAccount()
    {
        return \Modules\Accounting\Models\Account::expenses();
    }
}

if (!function_exists('revenuesAccount')) {
    
    function revenuesAccount()
    {
        return \Modules\Accounting\Models\Account::revenues();
    }
}

if (!function_exists('revenueAccount')) {
    
    function revenueAccount()
    {
        return \Modules\Accounting\Models\Account::revenues();
    }
}

if (!function_exists('ownersAccount')) {
    
    function ownersAccount()
    {
        return \Modules\Accounting\Models\Account::owners();
    }
}

if (!function_exists('liabilitiesAccount')) {
    
    function liabilitiesAccount()
    {
        return \Modules\Accounting\Models\Account::liabilities();
    }
}

if (!function_exists('safes')) {
    
    function safes($type = null)
    {
        if($type){
            return \Modules\Accounting\Models\Safe::where('type', $type)->get();
        }
        return \Modules\Accounting\Models\Safe::all();
    }
}

if (!function_exists('accounts')) {
    
    function accounts($all = true, $secondaries = false)
    {
        $accounts = \Modules\Accounting\Models\Account::getAll($all);
        if($secondaries){
            return $accounts->filter(function($account){ return $account->isSecondary(); });
        }else{
            return $accounts;
        }
        
    }
}

if (!function_exists('array_random')) {
    
    function array_random($array)
    {
        if(!empty($array)){
            return $array[array_rand($array)];
        }
        
    }
}

if (!function_exists('accountButton')) {
    
    function accountButton($account, $view = 'create', $action = null, $method = null, $btnSize = null)
    {
        $views = [
        'create' => [
        'title' => __('accounting::global.create'),
        'icon' => 'plus',
        'class' => 'primary'
        ],
        'update' => [
        'title' => __('accounting::global.edit'),
        'icon' => 'edit',
        'class' => 'warning'
        ],
        'confirm-delete' => [
        'title' => __('accounting::global.delete'),
        'icon' => 'trash',
        'class' => 'danger'
        ],
        'preview' => [
        'title' => __('accounting::global.preview'),
        'icon' => 'list',
        'class' => 'info'
        ],
        ];
        
        $btnSize = $btnSize ? 'btn-' . $btnSize : '';
        
        $builder = '<button class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . ' show-modal-account" data-view="'. $view . '" ';
        if ($action) {
            $builder .= 'data-action="' . $action . '" ';
        }
        if ($method) {
            $builder .= 'data-method="' . $method . '" ';
        }
        if ($account) {
            $builder .= 'data-id="' . $account->id . '" ';
            $builder .= 'data-name="' . $account->name . '" ';
            if($view == 'update'){
                $builder .= 'data-type="' . $account->type . '" ';
                $builder .= 'data-side="' . $account->side . '" ';
            }
            else if($view != 'create'){
                $builder .= 'data-type="' . $account->getType() . '" ';
                $builder .= 'data-side="' . $account->getSide() . '" ';
            }
            if($account->main_account){
                $builder .= 'data-main-id="' . $account->main_account . '" ';
                $builder .= 'data-main-name="' . $account->parent->name . '" ';
            }
            else{
                $builder .= 'data-main-name="'. __("accounting::global.not_found") . '" ';
            }
            
            if($account->final_account){
                $builder .= 'data-final-id="' . $account->final_account . '" ';
                $builder .= 'data-final-name="' . $account->final->name . '" ';
            }
            else{
                $builder .= 'data-final-name="'. __("accounting::global.not_found") . '" ';
            }
            
        }
        $builder .= ' >';
        $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
        $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
        $builder .= '</button>';
        
        return $builder;
    }
}

if (!function_exists('centerButton')) {
    
    function centerButton($center, $view = 'create', $action = null, $method = null, $btnSize = null)
    {
        $views = [
        'create' => [
        'title' => __('accounting::global.create'),
        'icon' => 'plus',
        'class' => 'primary'
        ],
        'update' => [
        'title' => __('accounting::global.edit'),
        'icon' => 'edit',
        'class' => 'warning'
        ],
        'delete' => [
        'title' => __('accounting::global.delete'),
        'icon' => 'trash',
        'class' => 'danger'
        ],
        'preview' => [
        'title' => __('accounting::global.preview'),
        'icon' => 'list',
        'class' => 'info'
        ],
        'show' => [
        'title' => __('accounting::global.show'),
        'icon' => 'eye',
        'class' => 'primary'
        ],
        ];
        
        $btnSize = $btnSize ? 'btn-' . $btnSize : '';
        if($view == 'show'){
            $builder = '<a href="' . route('centers.show', $center) . '" class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" ';
            
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</a>';
        }else{
            $builder = '<button class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" data-modal="center" data-view="'. $view . '" ';
            if ($action) {
                $builder .= 'data-action="' . $action . '" ';
            }
            else if(!$action && $view == 'update'){
                $builder .= 'data-action="'. route('centers.update', $center) . '" ';
            }
            else if(!$action && $view == 'delete'){
                $builder .= 'data-action="'. route('centers.destroy', $center) . '" ';
            }
            
            if ($method) {
                $builder .= 'data-method="' . $method . '" ';
            }
            else if(!$method && $view == 'update'){
                $builder .= 'data-method="PUT" ';
            }
            else if(!$method && $view == 'delete'){
                $builder .= 'data-method="DELETE" ';
            }
            
            if ($center) {
                $builder .= 'data-id="' . $center->id . '" ';
                $builder .= 'data-name="' . $center->name . '" ';
                if($view == 'update'){
                    $builder .= 'data-type="' . $center->type . '" ';
                }
                else if($view != 'create'){
                    $builder .= 'data-type="' . $center->getType() . '" ';
                }
            }
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</button>';
        }
        
        return $builder;
    }
}

if (!function_exists('safeButton')) {
    
    function safeButton($safe, $view = 'create', $action = null, $method = null, $btnSize = null)
    {
        $views = [
        'create' => [
        'title' => __('accounting::global.create'),
        'icon' => 'plus',
        'class' => 'primary'
        ],
        'update' => [
        'title' => __('accounting::global.edit'),
        'icon' => 'edit',
        'class' => 'warning'
        ],
        'delete' => [
        'title' => __('accounting::global.delete'),
        'icon' => 'trash',
        'class' => 'danger'
        ],
        'preview' => [
        'title' => __('accounting::global.preview'),
        'icon' => 'list',
        'class' => 'info'
        ],
        'show' => [
        'title' => __('accounting::global.show'),
        'icon' => 'eye',
        'class' => 'primary'
        ],
        ];
        
        $btnSize = $btnSize ? 'btn-' . $btnSize : '';
        if($view == 'show'){
            $builder = '<a href="' . route('safes.show', $safe) . '" class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" ';
            
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</a>';
        }else{
            $builder = '<button class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" data-modal="safe" data-view="'. $view . '" ';
            if ($action) {
                $builder .= 'data-action="' . $action . '" ';
            }
            else if(!$action && $view == 'update'){
                $builder .= 'data-action="'. route('safes.update', $safe) . '" ';
            }
            else if(!$action && $view == 'delete'){
                $builder .= 'data-action="'. route('safes.destroy', $safe) . '" ';
            }
            
            if ($method) {
                $builder .= 'data-method="' . $method . '" ';
            }
            else if(!$method && $view == 'update'){
                $builder .= 'data-method="PUT" ';
            }
            else if(!$method && $view == 'delete'){
                $builder .= 'data-method="DELETE" ';
            }
            
            if ($safe) {
                $builder .= 'data-id="' . $safe->id . '" ';
                $builder .= 'data-name="' . $safe->name . '" ';
                $builder .= 'data-balance="' . $safe->balance() . '" ';
                if($view == 'update'){
                    $builder .= 'data-type="' . $safe->type . '" ';
                }
                else if($view != 'create'){
                    $builder .= 'data-type="' . $safe->getType() . '" ';
                }
            }
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</button>';
        }
        
        return $builder;
    }
}

if (!function_exists('expenseButton')) {
    
    function expenseButton($expense, $view = 'create', $action = null, $method = null, $btnSize = null)
    {
        $views = [
        'create' => [
        'title' => __('accounting::global.create'),
        'icon' => 'plus',
        'class' => 'primary'
        ],
        'update' => [
        'title' => __('accounting::global.edit'),
        'icon' => 'edit',
        'class' => 'warning'
        ],
        'delete' => [
        'title' => __('accounting::global.delete'),
        'icon' => 'trash',
        'class' => 'danger'
        ],
        'preview' => [
        'title' => __('accounting::global.preview'),
        'icon' => 'list',
        'class' => 'info'
        ],
        'show' => [
        'title' => __('accounting::global.show'),
        'icon' => 'eye',
        'class' => 'primary'
        ],
        ];
        
        $btnSize = $btnSize ? 'btn-' . $btnSize : '';
        if($view == 'show'){
            $builder = '<a href="' . route('expenses.show', $expense) . '" class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" ';
            
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</a>';
        }else{
            $builder = '<button class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" data-modal="expense" data-view="'. $view . '" ';
            if ($action) {
                $builder .= 'data-action="' . $action . '" ';
            }
            else if(!$action && $view == 'update'){
                $builder .= 'data-action="'. route('expenses.update', $expense) . '" ';
            }
            else if(!$action && $view == 'delete'){
                $builder .= 'data-action="'. route('expenses.destroy', $expense) . '" ';
            }
            
            if ($method) {
                $builder .= 'data-method="' . $method . '" ';
            }
            else if(!$method && $view == 'update'){
                $builder .= 'data-method="PUT" ';
            }
            else if(!$method && $view == 'delete'){
                $builder .= 'data-method="DELETE" ';
            }
            
            if ($expense) {
                $builder .= 'data-id="' . $expense->id . '" ';
                $builder .= 'data-amount="' . $expense->amount . '" ';
                $builder .= 'data-details="' . $expense->details . '" ';
                $builder .= 'data-safe-id="' . $expense->safe->id . '" ';
                $builder .= 'data-safe-name="' . $expense->safe->name . '" ';
                $builder .= 'data-account-id="' . $expense->account->id . '" ';
                $builder .= 'data-account-name="' . $expense->account->name . '" ';
            }
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</button>';
        }
        
        return $builder;
    }
}

if (!function_exists('transferButton')) {
    
    function transferButton($transfer, $view = 'create', $action = null, $method = null, $btnSize = null)
    {
        $views = [
        'create' => [
        'title' => __('accounting::global.create'),
        'icon' => 'plus',
        'class' => 'primary'
        ],
        'update' => [
        'title' => __('accounting::global.edit'),
        'icon' => 'edit',
        'class' => 'warning'
        ],
        'delete' => [
        'title' => __('accounting::global.delete'),
        'icon' => 'trash',
        'class' => 'danger'
        ],
        'preview' => [
        'title' => __('accounting::global.preview'),
        'icon' => 'list',
        'class' => 'info'
        ],
        'show' => [
        'title' => __('accounting::global.show'),
        'icon' => 'eye',
        'class' => 'primary'
        ],
        ];
        
        $btnSize = $btnSize ? 'btn-' . $btnSize : '';
        if($view == 'show'){
            $builder = '<a href="' . route('transfers.show', $transfer) . '" class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" ';
            
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</a>';
        }else{
            $builder = '<button class="btn btn-'. $views[$view]['class'] . ' '. $btnSize . '" data-modal="transfer" data-view="'. $view . '" ';
            if ($action) {
                $builder .= 'data-action="' . $action . '" ';
            }
            else if(!$action && $view == 'update'){
                $builder .= 'data-action="'. route('transfers.update', $transfer) . '" ';
            }
            else if(!$action && $view == 'delete'){
                $builder .= 'data-action="'. route('transfers.destroy', $transfer) . '" ';
            }
            
            if ($method) {
                $builder .= 'data-method="' . $method . '" ';
            }
            else if(!$method && $view == 'update'){
                $builder .= 'data-method="PUT" ';
            }
            else if(!$method && $view == 'delete'){
                $builder .= 'data-method="DELETE" ';
            }
            
            if ($transfer) {
                $builder .= 'data-id="' . $transfer->id . '" ';
                $builder .= 'data-amount="' . $transfer->amount . '" ';
                $builder .= 'data-details="' . $transfer->details . '" ';
                $builder .= 'data-from-id="' . $transfer->from->id . '" ';
                $builder .= 'data-from-name="' . $transfer->from->name . '" ';
                $builder .= 'data-to-id="' . $transfer->to->id . '" ';
                $builder .= 'data-to-name="' . $transfer->to->name . '" ';
            }
            $builder .= ' >';
            $builder .= '<i class="fa fa-' . $views[$view]["icon"] . '"></i>';
            $builder .= '<span class="d-sm-none d-lg-inline">' . $views[$view]["title"] . '</span>';
            $builder .= '</button>';
        }
        
        return $builder;
    }
}

if (!function_exists('multi_implode')) {
    
    function multi_implode($glue, $array) {
        $ret = '';
        foreach ($array as $key => $value) {
            // for ($i=0; $i < count($array); $i++) {
            //     $item = $array[$i];
            if (is_array($value)) {
                $ret .= multi_implode($value, $glue) . $glue;
            } else {
                $ret .= $key . '=' . $value . $glue;
            }
        }
        
        $ret = substr($ret, 0, 0-strlen($glue));
        
        return $ret;
    }
}

if (!function_exists('multi_explode')) {
    
    function multi_explode($glue, $string) {
        $array = [];
        foreach (explode($glue, $string) as $str) {
            if (is_array(explode($glue, $str))) {
                $array = array_merge($array, explode($glue, $str));
            }else{
                $array[] = $str;
            }
        }
        
        return $array;
    }
}

if (!function_exists('logging')) {
    
    function logging($operation = 'create', $model = null, $details = '') {
        if($model){
            $model->checkMaxCopies();
        }
        if($operation == \App\Log::OPERATION_UPDATE){
            $newData = $model->getDirty();
            $oldData = $model->getOriginal();
            $model->fill($oldData);
        }
        $model_data = null;
        if($model){
            $model_data = json_encode($model->getOriginal());
        }
        $log = \App\Log::create([
        'operation' => $operation,
        'details' => $details,
        'model' => $model_data,
        'logable_type' => $model ? get_class($model) : null,
        'logable_id' => $model ? $model->id : null,
        ]);
        
        if($operation == \App\Log::OPERATION_UPDATE){
            $model->fill($newData);
        }
        return $log;
        // if (!auth()->guard('office')->check()) {
        // }
        // return null;
    }
}
if (!function_exists('startsWith')) 
{
    
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
if (!function_exists('endsWith')) 
{
    
    function endsWith ($string, $endString) 
    { 
        $len = strlen($endString); 
        if ($len == 0) { 
            return true; 
        } 
        return (substr($string, -$len) === $endString); 
    }
}