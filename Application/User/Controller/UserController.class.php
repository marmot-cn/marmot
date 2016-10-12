<?php
namespace User\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;
use System\Interfaces\IView;

class UserController extends Controller
{
   
    use JsonApiController;
    
    public function get(string $ids = '')
    {
        var_dump("user controller get:".$ids);
        return true;
    }

    public function post()
    {
        $this->getResponse()->setStatusCode(201);
        $data = $this->getRequest()->post();

        $this->render(
            new class($data) implements IView{
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function display()
            {
                return $this->data;
            }
            }
        );
        var_dump("user controller post");
        return true;
    }

    public function put(int $id)
    {
        $data = $this->getRequest()->put('data');
        print_r($data);
        var_dump("user controller put:".$id);
        return true;
    }

    public function delete(int $id)
    {
        var_dump("user controller delete:".$id);
        return true;
    }
}
