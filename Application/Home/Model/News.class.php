<?php
namespace Home\Model;

/**
 * 用于测试演示类
 */
class News 
{   
    //新闻id
    private $id;

    //新闻标题
    private $title;

    //新闻内容
    private $content;

    //评论列表
    private $comments;

    public function __construct(int $id, string $title) 
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = '';
        $this->comments = array();
    }

    public function __destruct()
    {
        unset($this->id);
        unset($this->title);
        unset($this->content);
        unset($this->comments);
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int 
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function setContent(string $content) 
    {
        $this->content = $content;
    }

    public function getContent() : string 
    {
        return $this->content;
    }

    public function setComments(array $comments)
    {
        $this->comments = $comments;
    }

    public function getComments() : array
    {
        return $this->comments;
    }
}
