<?php
namespace Home\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;

//use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Message\AMQPMessage;

class IndexController extends Controller
{


    use JsonApiController;
    /**
     * @codeCoverageIgnore
     */
    public function index()
    {
        var_dump("Hello World");
        return true;
    }
}
