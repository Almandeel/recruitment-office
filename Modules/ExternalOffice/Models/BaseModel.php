<?php

namespace Modules\ExternalOffice\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\Logable;

class BaseModel extends Model
{
    // use Logable;
    
    public function equals($modal){
        return $this->id == $modal->id && $this->cretaed_at == $modal->cretaed_at && $this->updated_at == $modal->updated_at;
    }
    
    public function getPrimaryKey(){
        return $this->primaryKey;
    }
    
    public function money($column = 'amount', $currency = 'none'){
        if ($currency != 'none') {
            return number_format($this->$column, 2) . ' ' . __('global.currency_' . $currency);
        }
        return '$' . number_format($this->$column, 2);
    }
    
    public static function last(){
        return static::all()->last();
    }
}