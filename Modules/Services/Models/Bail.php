<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExternalOffice\Models\Cv;

class Bail extends Model
{
    protected $fillable = [];
    

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
    

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function x_customer()
    {
        return $this->belongsTo(Customer::class, 'x_customer');
    }
    

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    
    public function x_contract()
    {
        return $this->belongsTo(Contract::class, 'x_contract');
    }
    
}
