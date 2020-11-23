<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Authable;
use App\User;
class Log extends Model
{
    public const OPERATION_CREATE = 'create';
    public const OPERATION_UPDATE = 'update';
    public const OPERATION_DELETE = 'delete';
    public const OPERATION_LOGIN  = 'login';
    public const OPERATION_LOGOUT = 'logout';
    
    public const OPERATIONS = [
    self::OPERATION_CREATE,
    self::OPERATION_UPDATE,
    self::OPERATION_DELETE,
    self::OPERATION_LOGIN,
    self::OPERATION_LOGOUT,
    ];
    
    public function getOperation(){
        return __('logs.operations.' . $this->operation);
    }
    
    public function getTitle(){
        $search[] = '__id__';
        $replace[] = $this->logable_id;
        if($this->logable && ($this->operation == self::OPERATION_LOGIN || $this->operation == self::OPERATION_LOGOUT)){
            $search[] = '__name__';
            $replace[] = $this->logable->name;
        }
        
        $title = str_replace($search, $replace,  __('logs.' . $this->logable()->getRelated()->getTable() . '.' . $this->operation));
        $title = strlen($title) ? $title : $this->getOperation();
        return $title;
    }
    protected $table = 'logs';
    protected $fillable = ['operation', 'model', 'details', 'user_id', 'user_type', 'logable_type', 'logable_id'];
    
    public function logable()
    {
        return $this->morphTo();
    }
    
    public function isRestoreable(){
        return ($this->isModel() && ($this->operation == self::OPERATION_CREATE || $this->operation == self::OPERATION_UPDATE || $this->operation == self::OPERATION_DELETE));
    }
    
    public function isModel(){
        return $this->logable_id != null;
    }
    
    public function getModel(){
        return __('logs.models.' . $this->logable()->getRelated()->getTable());
    }
    
    /**
    * Make a model from stdObject.
    *
    * @param  stdClass $std
    * @param  array    $fill
    * @param  boolean  $exists
    * @return \Illuminate\Database\Eloquent\Model
    */
    public static function newFromStd($std, $fill = ['*'], $exists = true)
    {
        $instance = new static;
        
        $values = ($fill == ['*'])
        ? (array) $std
        : array_intersect_key( (array) $std, array_flip($fill));
        
        // fill attributes and original arrays
        $instance->setRawAttributes($values, true);
        
        $instance->exists = $exists;
        
        return $instance;
    }
    
    public function getModelData(){
        $data = [];
        if ($this->isModel()) {
            $std = json_decode($this->model, true);
            foreach ($std as $key => $value) {
                if(!is_array($value) && !is_object($value)){
                    $data[$key] = $value;
                }
            }
            
            return $data;
        }
        
        return $data;
    }
    
    public function restore($include_related = false)
    {
        
        $std = json_decode($this->model, true);
        $instance = $this->logable ? $this->logable : $this->logable()->getRelated();
        foreach ($std as $key => $value) {
            if(!is_array($value) && !is_object($value) && !is_null($value)){
                $instance->$key = $value;
            }else{
                // $attributes = is_object($value) ? (array) $value : $value;
                // $query = 'INSERT INTO ' . $key . ' ';
                // $keys = array_keys($attributes);
                // $values = array_values($attributes);
                // $place_holders = count($values) ? implode(', ', array_fill(0, count($values), '?')) : '';
                // $fields = implode(', ', $keys);
                // $query .= count($keys) ? '(' . $fields . ')' : '';
                // $query .= count($values) ? ' VALUES(' . $place_holders . ')' : '';
                // dd($query);
                // \DB::insert($query, $values);
                if ($include_related) {
                    $related = $instance->$key ? $instance->$key : $instance->$key()->getRelated();
                    $attributes = is_object($value) ? (array) $value : $value;
                    $related->fill($attributes);
                    $related->save();
                }
            }
        }
        $instance->restored = true;
        $instance->save();
        return $instance;
    }
    
    
    public function user()
    {
        if(is_null($this->user_type)) return $this->belongsTo(User::class) ;
        return $this->belongsTo($this->user_type);
    }

    public static function create($attributes)
    {
        if (auth()->check()) {
            if (!(array_key_exists('user_id', $attributes))) {
                $attributes['user_id'] = auth()->user()->getKey();
            }
            if (!(array_key_exists('user_type', $attributes))) {
                $attributes['user_type'] = get_class(auth()->user());
            }

            return static::query()->create($attributes);
        }
    }

    // public static function boot(){
    //     static::boot();
    // }
    
}