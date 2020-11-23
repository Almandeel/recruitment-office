<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseCv extends Model
{
    protected $table = 'cv_warehouse';
    protected $fillable = ['cv_id', 'warehouse_id', 'status', 'entry_date' ,'exit_date', 'entry_note','exit_note'];

    public function cv() {
        return $this->belongsTo('Modules\ExternalOffice\Models\Cv');
    }
}
