<?php

namespace Modules\Accounting\Models;

class Currency extends BaseModel
{
    

    protected $fillable = [
        'name', 'short', 'sample'
    ];
    public function rates(){
        return $this->hasMany('App\Rate', 'from_id');
    }
    public function delete()    
    {
        \DB::transaction(function() 
        {   
            $this->rates()->delete();
            parent::delete();
        });
    }
}
