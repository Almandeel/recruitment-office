<?php
namespace App\Traits;
/**
*  Accountable Trait
*/
trait Accountable
{
    
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }
}