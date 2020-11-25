<?php

namespace Modules\Services\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
use Modules\ExternalOffice\Models\Country;
use Modules\Services\Models\Contract;
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
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class);
    }

    public function getMarketerVoucherAttribute()
    {
        return $this->vouchers->where('marketer_id', $this->marketer_id)->first();
    }
    
}