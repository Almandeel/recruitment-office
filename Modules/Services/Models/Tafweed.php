<?php

namespace Modules\Services\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
use Modules\Services\Models\Customer;
class Tafweed extends Model
{
    use Attachable, Voucherable;
    
    public function getName()
    {
        return $this->customer->name ?? '';
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
}