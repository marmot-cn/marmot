<?php
//powered by chloroplast
namespace System\View;

use System\Interfaces\IResponseFormatter;

class JsonApiResponseFormatter implements IResponseFormatter
{

    public function format($response)
    {

        $response->addHeader('Content-Type', 'application/vnd.api+json');
        if ($response->data !== null) {
            $response->content = $response->data;
        }
    }
}
