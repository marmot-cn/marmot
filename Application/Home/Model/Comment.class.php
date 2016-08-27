<?php
namespace Home\Model;

Class Comment 
{
    private $id;
    private $content;

    public function __construct(int $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function __destruct()
    {
        unset($this->id);
        unset($this->content);
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int 
    {
        return $this->id;
    }

    public function setContent(string $content) 
    {
        $this->content = $content;
    }

    public function getContent() : string
    {
        return $this->content;
    }
}
