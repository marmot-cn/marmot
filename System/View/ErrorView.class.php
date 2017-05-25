<?php
namespace System\View;

use System\Interfaces\IView;
use Common\View\JsonApiView;
use Marmot\Core;

use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Document\Error as JsonApiError;

/**
 * @codeCoverageIgnore
 */
class ErrorView implements IView
{
    use JsonApiView;

    public function display()
    {
        return $this->jsonApiError();
    }

    private function jsonApiError()
    {
        $lasetError = Core::getLastError();

        $error = new JsonApiError(
            $lasetError->getId(),
            new Link($lasetError->getLink()),
            $lasetError->getStatus(),
            $lasetError->getCode(),
            $lasetError->getTitle(),
            $lasetError->getDetail(),
            ['source' => $lasetError->getSource()],
            ['meta'   => $lasetError->getMeta()]
        );

        return Encoder::instance(
            array(),
            new EncoderOptions(JSON_PRETTY_PRINT, $_SERVER['HTTP_HOST'])
        )->encodeError($error);
    }
}
