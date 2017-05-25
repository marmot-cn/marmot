<?php
namespace System\Classes;

class Error
{
    private $id;

    private $link;

    private $status;

    private $code;

    private $title;

    private $detail;
    
    private $source;
    
    private $meta;

    public function __construct(
        int $id = 0,
        string $link = '',
        string $status = '',
        string $code = '',
        string $title = '',
        string $detail = '',
        array $source = array(),
        array $meta = array()
    ) {
        $this->id = $id;
        $this->link = $link;
        $this->status = $status;
        $this->code = $code;
        $this->title = $title;
        $this->detail = $detail;
        $this->source = $source;
        $this->meta = $meta;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getLink() : string
    {
        return $this->link;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getCode() : string
    {
        return $this->code;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getDetail() : string
    {
        return $this->detail;
    }

    public function getSource() : array
    {
        return $this->source;
    }

    public function getMeta() : array
    {
        return $this->meta;
    }
}
