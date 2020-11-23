<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;

class BillCv extends BaseModel
{
    protected $table = 'bill_cv';
    protected $fillable = [
      'bill_id',
      'cv_id',
      'amount',
      'amount_in_riyal',
    ];

    public function getAmountInRiyalAttribute($amount)
    {
      $voucher = $this->cv->voucher;
      $entry = $voucher ? $voucher->entry : null;
      return $entry ? $entry->amount : 0;
      // return $amount;
    }

    public function bill()
    {
    	return $this->belongsTo(Bill::class);
    }

    public function cv()
    {
    	return $this->belongsTo(Cv::class);
    }
}