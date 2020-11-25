<?php

namespace Modules\Services\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Traits\Voucherable;
class Tafweed extends Model
{
    use Attachable, Voucherable;
    public function getName()
    {
        return '';
    }
}
