<?php

namespace Modules\Employee\Models;


class Department extends BaseModel
{
    
    protected $table = 'departments';
    protected $fillable = ['title'];
    
    
    public function employees() {
        return $this->hasMany('Modules\Employee\Models\Employee');
    }
    
    public function isDeleteable(){
        return $this->employees->count() < 1;
    }

    public function delete(){
        // if($this->employees){
        //     return false;
        // }
        $result = parent::delete();
        return $result;
    }
}