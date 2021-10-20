<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use Spatie\Activitylog\Models\Activity;

class LogsController extends BaseController
{
    public function logs()
    {
        $data = Activity::with('causer')->get();
        return $this->sendResponse($data,'Logs List');
    }
}
