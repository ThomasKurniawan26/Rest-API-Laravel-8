<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($message, $data = [] ){
        $user = request() -> user();
        return response() -> json([$message, $data, $user]);
    }
    
    public function responseError($message){
        return response() -> json([$message, $data = null]);
    }

}
