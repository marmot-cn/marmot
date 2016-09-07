<?php
namespace Common\Controller;

use Neomerx\JsonApi\Http\Request;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Exceptions\JsonApiException;

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

    public function checkParametersRule(
        $allowUnrecognised = true,
        $includePaths = array(),
        $fieldSetTypes = array(),
        $sortParameters = array(),
        $pagingParameters = array(),
        $filteringParameters = array()
    ) {
        $factory = new Factory();
        $this->parametersChecker = $factory->createQueryChecker(
            $allowUnrecognised,
            $includePaths,
            $fieldSetTypes,
            $sortParameters,
            $pagingParameters,
            $filteringParameters
        );
    }

    public function checkParameters($parameters)
    {
        try {
            $this->parametersChecker->checkQuery($parameters);
        } catch (JsonApiException $expection) {
            // var_dump($expection->getErrors());
            return false;
        }
    }
}
