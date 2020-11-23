<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Logable;

class BaseModel extends Model
{
    use Logable;
    
    public function equals($modal){
        return $this->id == $modal->id && $this->cretaed_at == $modal->cretaed_at && $this->updated_at == $modal->updated_at;
    }
    
    public function getPrimaryKey(){
        return $this->primaryKey;
    }
}