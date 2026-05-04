<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\UserService;

class UserController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new UserService();
        $this->itemVariable = 'user';
    }
}
