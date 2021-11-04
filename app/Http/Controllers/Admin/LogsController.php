<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class LogsController extends Controller
{
    public function logs()
    {
        $data = Activity::with('causer')->get();
        return $this->sendResponse($data,'Logs List');
    }
}
