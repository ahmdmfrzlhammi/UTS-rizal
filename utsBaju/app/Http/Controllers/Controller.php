<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $default_response=[
        'success'=>true,
        'message'=>'',
        'data'=>null
    ];

    public function __constract($default_response=null)
    {
        if($default_response !=null){
            $this->default_response = $default_response;
        }
    }
}
