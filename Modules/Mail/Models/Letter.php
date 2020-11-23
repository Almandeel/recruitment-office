<?php

namespace Modules\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Attachable;

class Letter extends Model
{
    public const BOX_INBOX = 1;
    public const BOX_OUTBOX = 2;
    public const BOX_DRAFTS = 3;
    public const BOXES = [
    self::BOX_INBOX => 'الوارد',
    self::BOX_OUTBOX => 'المرسل',
    self::BOX_DRAFTS => 'المحفوظات',
    ];
    use Attachable;
    protected $fillable = ['title',	'content',	'user_id',	'status', 'letter_id'];
    
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function users() {
        return $this->belongsToMany('App\User')->withPivot(['user_id', 'letter_id', 'status', 'box']);
    }
    
    public function receivers() {
        $letter = $this;
        return $this->users->filter(function($user) use ($letter){
            return $user->pivot->user_id !== $letter->user_id;
        });
    }
    
    public function sent($id = null){
        $userId = $id ? $id : auth()->user()->getKey();
        return $this->user_id === $userId;
    }
    
    public function received($id = null){
        $userId = $id ? $id : auth()->user()->getKey();
        return $this->user_id !== $userId;
    }
    
    public function participates() {
        return $this->hasMany('Modules\Mail\Models\LetterUser');
    }
    
    public function replyLetters() {
        return $this->hasMany('Modules\Mail\Models\Letter');
    }
    
    public static function create(array $attributes = [])
    {
        // dd($attributes);
        $attributes['user_id'] = auth()->user()->getKey();
        $model = static::query()->create($attributes);
        LetterUser::create([
        'user_id' => auth()->user()->getKey(),
        'letter_id' => $model->id,
        'box' => self::BOX_OUTBOX
        ]);
        return $model;
    }
    
    public function delete(){
        foreach ($this->attachments as $attachment) {
            $attachment->delete();
        }
        
        $this->users()->detach();
        
        $result =  parent::delete();
        return $result;
    }
}