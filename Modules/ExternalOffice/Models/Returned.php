<?php

namespace Modules\ExternalOffice\Models;
use Modules\Main\Models\Office;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Attachable;
class Returned extends BaseModel
{
    use Attachable;
    
    protected $table = 'returns';
    protected $fillable = ['cv_id', 'user_id', 'advance_id', 'payed', 'damages'];
    
    public function getType(){
        return is_null($this->advance_id) ? 'free' : 'payed';
    }
    
    public function displayType(){
        return __('accounting::global.return_' . $this->getType());
    }
    
    public function displayStatus(){
        if (is_null($this->advance_id)) {
            return __('accounting::global.return_' . $this->getType());
        }
        
        return $this->advance->displayStatus();
    }
    
    public function getAmount($formated = false){
        $amount = is_null($this->advance_id) ? 0 : $this->advance->amount;
        if($formated && is_null($this->advance_id)) {
            return $this->displayType();
        }
        else if($formated && !is_null($this->advance_id)) {
            return number_format($amount, 2);
        }
        return $amount;
    }

    public function totalAmount(){
        return money_formats($this->payed + $this->damages);
    }
    
    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }
    
    public function office()
    {
        return $this->cv->belongsTo(Office::class);
    }
}