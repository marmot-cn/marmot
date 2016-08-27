<?php
namespace Home\View;

use \Neomerx\JsonApi\Schema\SchemaProvider;
use Home\Model\Comment;

class CommentSchema extends SchemaProvider 
{
    protected $resourceType = 'comment';

    public function getId($comment) : int
    {
        return $comment->getId();
    }

    public function getAttributes($comment) : array
    {
        return [
            'content' => $comment->getContent(),
        ];
    }
}
