<?php
namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use App\Log;
use App\User;
trait Logable {
    public function getMaxCopies(){return  env('LOG_MAX_COPIES', 5); }
    public function getMaxDays(){return  env('LOG_MAX_DAYS', 7); }
    public $restored = false;
    public function getFillable()
    {
        // return Schema::getColumnListing($this->getTable());
        $attributes = $this->fillable;
        if(!array_key_exists($this->getPrimaryKey(), $attributes)){
            $attributes[] = $this->getPrimaryKey();
        }
        if($this->created_at) $attributes[] = $this->created_at->toDateTimeString();
        if($this->updated_at) $attributes[] = $this->updated_at->toDateTimeString();
        
        return $attributes;
    }
    
    public function logs()
    {
        return $this->morphMany(Log::class, 'logable');
    }
    
    public function getPrimaryKey(){
        return $this->primaryKey;
    }
    
    public function checkMaxCopies(){
        $this->checkMaxDays();
        if($this->logs->count() >= $this->getMaxCopies()){
            $this->logs->sortByDesc('created_at')->first()->delete();
        }
    }
    
    public function checkMaxDays(){
        if($this->loggingDays() >= $this->getMaxDays()){
            Log::truncate();
        }
        
        return true;
    }
    
    public function loggingDays(){
        // $first = Log::limit(1)->get()->sortBy('created_at');//\DB::select('SELECT * FROM logs ORDER BY created_at ASC LIMIT 1');
        // $last = Log::limit(1)->get()->sortByDesc('created_at');//\DB::select('SELECT * FROM logs ORDER BY created_at DESC LIMIT 1');
        $first = Log::orderBy('created_at', 'ASC')->limit(1)->get()->first();
        $last = Log::orderBy('created_at', 'DESC')->limit(1)->get()->first();
        
        // $first = count($first) ? $first[0] : null;
        // $last = count($last) ? $last[0] : null;
        // $last = $last ? $last : date('Y-m-d');
        // $logs = Log::all(); //whereNotNull('created_at')->get();
        if($first){
            $start_date = $first->created_at;
            $end_date = $last->created_at;
            
            $start_date = \Carbon\Carbon::parse(date('Y-m-d', strtotime($start_date)));
            $end_date = \Carbon\Carbon::parse(date('Y-m-d', strtotime($end_date)));
            return $start_date->diffInDays($end_date);
        }
        
        return 0;
    }
    public static function bootLogable(){
        static::saved(function($model){
            // $dirty = $model->getDirty();
            
            // if (count($dirty) > 0)
            // {
            //     $operation = $model->wasRecentlyCreated ? 'create' : 'update';
            //     $log = logging($operation, $model);
            // }
        });
        
        /*
        static::created(function($model){
        });
        
        static::updating(function($model){
        });
        */
        
        static::deleting(function($model){
            // if (!auth()->guard('office')->check()) {
            //     $model->checkMaxCopies();
            //     $log = Log::create(['operation' => 'delete', 'model' => $model->toJson(), 'logable_type' => get_class($model), 'logable_id' => $model->id]);
            // }
        });
    }
    
    public function auth(){
        $user = null;
        $logs = $this->logs;
        if($logs->count()){
            $user = $logs->first()->user;
        }else{
            $user = new User;
            $user->name = 'لا يوجد';
        }
        
        return $user;
    }
}