<?php
namespace Home\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;
use Marmot\Core;

//use Fluent\Logger\FluentLogger;
use Monolog\Logger;
use Monolog\Handler\MongoDBHandler;
use System\Extension\Monolog\FluentdHandler;

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

    /**
     * @codeCoverageIgnore
     */
    public function error()
    {
        $this->displayError();
        return false;
    }
}
