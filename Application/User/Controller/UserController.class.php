<?php
namespace User\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;

class UserController extends Controller
{
   
    use JsonApiController;
    
    public function get(string $ids = '')
    {
        var_dump("user controller");
        return true;
    }
}
