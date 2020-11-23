<?php
namespace App\Traits;
use App\Note;
trait Noteable{
    use Attachable;
    
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}