<?php
namespace App\Traits;
use App\User;
/**
*  Accountable Trait
*/
trait Authable
{
    
    public function auth()
    {
        return $this->morphToMany(User::class, 'authable')->first();
    }
    
    public static function bootAuthable()
    {
        static::creating(function($model){
            // dd($model);
        });
        
        static::created(function($model){
            // $attributes = [];
            // if (auth()->user()) {
            //     $attributes['user_id'] = auth()->user()->getKey();
            // }else{
            //     if(User::super()){
            //         $attributes['user_id'] = User::super()->id;
            //     }
            // }
            // $attributes['authable_id'] = $model->id;
            // $attributes['authable_type'] = get_class($model);
            // \DB::table('authables')->insert($attributes);
            
        });
        
        static::updating(function($model){
            // ... code here
        });
        
        static::updated(function($model){
            // ... code here
        });
        
        static::deleting(function($model){
            // \DB::table('authables')->where('authable_id', $model->id)->delete();
        });
        
        static::deleted(function($model){
            // ... code here
        });
    }
}