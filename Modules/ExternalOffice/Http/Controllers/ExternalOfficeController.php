<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Modules\ExternalOffice\Models\Advance;
use Modules\ExternalOffice\Models\Bill;

class ExternalOfficeController extends BaseController
{
    public function __invoke()
    {
        return view('externaloffice::dashboard');
    }
}
