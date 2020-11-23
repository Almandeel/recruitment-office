<?php
namespace Modules\Accounting\Traits;
use Modules\Accounting\Models\Year;
/**
*  Yearable Trait
*/
trait Yearable
{
    
    public function year()
    {
        // return $this->morphToMany(Year::class, 'yearable')->first();
        return $this->belongsTo(Year::class);
    }
    // public function getFillable(){
    //     $fillable = $this->getFillable();
    //     if(!in_array('year_id', $fillable)){
    //         $fillable[] = 'year_id';
    //     }
    //     return $fillable;
    // }
    public static function generateId($model, $year = null, $id = null){
        // $counter = $count ? $count : \DB::table('yearables')->where('yearable_type', get_class($model))->count();
        // $counter = ($counter <= 0) ? 1 : $counter;
        // $id = yearId() . $counter;
        // if(\DB::table($model->getTable())->where('id', $id)->get()->count()){
        //     $id = static::generateId($model, $count + 1);
        // }
        // return $id;
        $class = get_class($model);
        if($id){
            $id += 1;
        }else{
            $year = is_null($year) ? year() : $year;
            $rows = $class::where('year_id', yearId())->get();
            // $count = $rows->count() + 1;
            // $id = $year->id . $count;
            $id = $rows->count() ? ($rows->last()->id + 1) : $year->id . '1';
        }
        if($class::find($id)){
            return static::generateId($model, $year, $id);
        }
        return $id;
    }
    
    public static function bootYearable()
    {
        
        static::creating(function($model){
            if(!$model->restored){
                // $yearables = \DB::table('yearables')->where('yearable_type', get_class($model))->get();
                $year = year();
                $model->id = static::generateId($model, $year);
                $model->year_id = $year->id;
            }
        });
        
        static::created(function($model){
            // $attributes = [];
            // $attributes['year_id'] = yearId();
            // $attributes['yearable_id'] = $model->id;
            // $attributes['yearable_type'] = get_class($model);
            // \DB::table('yearables')->insert($attributes);
            
        });
        
        static::updating(function($model){
            // ... code here
        });
        
        static::updated(function($model){
            // ... code here
        });
        
        static::deleting(function($model){
            // \DB::table('yearables')->where('yearable_id', $model->id)->delete();
        });
        
        static::deleted(function($model){
            // ... code here
        });
    }
}