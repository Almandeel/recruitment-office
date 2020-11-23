<?php

namespace Modules\Services\Models;


class ContractCustomer extends BaseModel
{
    protected $table = 'contract_customer';
    protected $fillable = ['contract_id', 'customer_id', 'cv_id', 'status'];

    public function contract() {
        return $this->belongsTo('Modules\Services\Models\Contract', 'contract_id');
    }

    public function customer() {
        return $this->belongsTo('Modules\Services\Models\Customer', 'customer_id');
    }
}
