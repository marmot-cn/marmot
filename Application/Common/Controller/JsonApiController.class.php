<?php
namespace Common\Controller;

use Neomerx\JsonApi\Http\Request;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Neomerx\JsonApi\Encoder\Encoder;

use System\View\ErrorView;
use Marmot\Core;

/**
 * @codeCoverageIgnore
 */
trait JsonApiController
{

    private $parametersChecker;

    private function getPsr7Request($nonPsr7request)
    {
        $psr7request  = new Request(function () use ($nonPsr7request) {
            return $nonPsr7request->getMethod();
        }, function ($name) use ($nonPsr7request) {
            return $nonPsr7request->getHeader($name);
        }, function () use ($nonPsr7request) {
            return $nonPsr7request->getQueryParams();
        });

        return $psr7request;
    }

    public function getParameters()
    {
        $psr7request = $this->getPsr7Request($this->getRequest());
        $factory = new Factory();
        return $factory->createQueryParametersParser()->parse($psr7request);
    }

    public function getSort()
    {
        $sort = array();
        $sortParameters = $this->getParameters()->getSortParameters();

        if (!empty($sortParameters)) {
            foreach ($sortParameters as $sortParameter) {
                $sort[$sortParameter->getField()] = $sortParameter->isAscending() ? 1 : -1;
            }
        }
        return $sort;
    }

    public function getIncludePaths()
    {
        return $this->getParameters()->getIncludePaths();
    }

    public function displayError()
    {
        $this->getResponse()->setStatusCode(Core::getLastError()->getStatus());
        $this->render(new ErrorView());
    }
}
