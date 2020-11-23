<?php
namespace App\Traits;
use App\Log;
trait Trackable
{

    public static function bootTrackable()
    {
        static::creating(function ($model) {
            // $model->
        });
        
        static::updating(function ($model) {
            // bleh bleh
        });
        
        static::deleting(function ($model) {
            // bluh bluh
        });
    }
}