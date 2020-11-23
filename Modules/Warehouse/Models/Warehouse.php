<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
use App\User;
class Warehouse extends BaseModel
{
    use Voucherable;
    
    protected $table = 'warehouses';
    protected $fillable = ['name', 'address', 'phone'];
    
    
    public function warehouseUsers() {
        return $this->hasMany('Modules\Warehouse\Models\WarehouseUser', 'warehouse_id');
    }
    
    public function warehouseCv() {
        return $this->hasMany('Modules\Warehouse\Models\WarehouseCv');
    }

    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}