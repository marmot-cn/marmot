<?php
namespace System\Strategy\MediaTypes;

use Marmot\Core;
use System\Interfaces\IMediaTypeStrategy;
use System\Classes\Request;

class JsonapiStrategy implements IMediaTypeStrategy
{
    
    private $request;

    public function validate(Request $request) : bool
    {
        $this->request = $request;
        return $this->validateAcceptHeader() && $this->validateContent();
    }

    private function validateAcceptHeader(): bool
    {
        $accept = $this->request->getHeader('accept', '');
        $accept = explode(',', $accept);

        if (in_array('application/vnd.api+json', $accept)) {
            return true;
        }
        
        $types = [];
        if (!empty($accept)) {
            foreach ($accept as $each) {
                $types[] = explode(';', $each)[0];
            }
        }

        if (in_array('*/*', $types)) {
            return true;
        }

        Core::setLastError(NOT_ACCEPTABLE_MEDIA_TYPE);
        return false;
    }

    private function validateContent() : bool
    {
        if (!$this->request->isPostMethod() && !$this->request->isPutMethod()) {
            return true;
        }

        return $this->validateContentTypeHeader() && $this->validateRawBody();
    }

    private function validateContentTypeHeader() : bool
    {
        $contentType = $this->request->getHeader('content-type', '');
        $contentType = explode(',', $contentType);

        if (in_array('application/vnd.api+json', $contentType)) {
            return true;
        }
        
        $types = [];
        if (!empty($contentType)) {
            foreach ($contentType as $each) {
                $types[] = explode(';', $each)[0];
            }
        }

        if (in_array('*/*', $types)) {
            return true;
        }

        Core::setLastError(UNSUPPORTED_MEDIA_TYPE);
        return false;
    }

    private function validateRawBody() : bool
    {
        if (is_null(@json_decode($this->request->getRawBody()))) {
            Core::setLastError(INCORRECT_RAW_BODY);
            return false;
        }

        return true;
    }

    public function decode($rawData)
    {
        return json_decode($rawData, true);
    }
}
