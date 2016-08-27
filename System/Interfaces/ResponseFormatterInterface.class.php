<?php
namespace System\Interfaces;

interface ResponseFormatterInterface
{
    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format($response);
}
