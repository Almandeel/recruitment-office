<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseUser extends Model
{
    protected $table = 'user_warehouse';
    protected $fillable = ['user_id', 'warehouse_id'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
