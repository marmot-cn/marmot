<?php
//powered by chloroplast
namespace System\View;

use System\Interfaces\ResponseFormatterInterface;

class JsonApiResponseFormatter implements ResponseFormatterInterface
{

    public function format($response)
    {

        $response->addHeader('Content-Type', 'application/vnd.api+json');
        if ($response->data !== null) {
            $response->content = $response->data;
        }
    }
}
