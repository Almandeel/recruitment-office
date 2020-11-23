<?php

namespace Modules\ExternalOffice\Models;

use App\Traits\{Attachable, Statusable};
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
use Modules\Main\Models\Office;

class Bill extends BaseModel
{
  use Attachable;
  use Statusable;
  use Voucherable;

  protected $fillable = [
    'notes',
    'amount',
    'status',
    'riyal_per_dollar',
    'user_id',
    'office_id',
  ];

  public function office()
  {
    return $this->belongsTo(Office::class);
  }

  // public function displayStatus(){
  //   $status = $this->isPayed() ? 'payed' : 'waiting';
  //   if (auth()->guard('office')->check()) {
  //     return $status;
  //   }
  //   return __('accounting::global.' . $status);
  // }

  public function payed($formated = false){
    $payed = $this->vouchers->filter(function($voucher){
      return $voucher->isChecked();
    })->sum('amount');
    if($formated){
      return number_format($payed, 2);
    }
    return $payed;
  }

  public function remain($formated = false){
    $remain = $this->amount - $this->payed();
    if($formated){
      return number_format($remain, 2);
    }
    return $remain;
  }

  public function isPayed(){
    return $this->remain() == 0;
  }

  public function cvBill()
  {
    return $this->hasMany('Modules\ExternalOffice\Models\BillCv');
  }

  public function scopeOfficeBills($query)
  {
    $query->where('office_id', '=', auth()->guard('office')->user()->office_id);
  }

    public function cvPivot($cv_id)
    {
        return BillCv::where('bill_id', $this->id)->where('cv_id', $cv_id)->first();
    }

  public function cvs()
  {
      return $this->belongsToMany(Cv::class)->withPivot('amount');
  }
  
}
