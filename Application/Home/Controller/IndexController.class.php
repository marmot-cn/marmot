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
        $adapter = new class extends \Common\Adapter\Document\DocumentAdapter
        {
            public function __construct()
            {
                parent::__construct('test', 'demo');
            }
        };

        $document = new class extends \Common\Model\Document
        {
        };

        $document->setData(array('test'));
        $adapter->add($document);
        var_dump($document->getId());
        //$document->setId('5a4217f95ddd37000b5cb152');
        //$adapter->fetchOne($document);

        //var_dump($document->getData());exit();

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
