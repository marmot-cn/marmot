<?php
namespace Home\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;
use Marmot\Core;

class IndexController extends Controller
{

    use JsonApiController;
    
    /**
     * @codeCoverageIgnore
     */
    public function index()
    {
        var_dump("Hello World marmot");
        return true;
    }
}
