<?php

namespace Modules\Customer\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;

class Bill extends Model
{
  use Attachable;
  use Voucherable;

  protected $fillable = [
    'notes',
    'amount',
    'user_id',
    'office_id',
  ];

  public function cvBill()
  {
    return $this->hasMany('Modules\ExternalOffice\Models\BillCv');
  }

  public function scopeOfficeBills($query)
  {
    $query->where('office_id', '=', auth()->guard('office')->user()->office_id);
  }
}
