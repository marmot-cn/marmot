<?php
namespace Home\View;

use \Neomerx\JsonApi\Schema\SchemaProvider;

class NewsSchema extends SchemaProvider 
{
    protected $resourceType = 'news';

    public function getId($news) : int
    {
        return $news->getId();
    }

    public function getAttributes($news) : array
    {
        return [
            'title' => $news->getTitle(),
            'content' => $news->getContent(),
        ];
    }

    public function getRelationships($news, $isPrimary, array $includeList)
    {
        return [
            'comments' => [self::DATA => $news->getComments()],
        ];
    }

    public function getIncludePaths()
    {
        return [
            'comments.content',
        ];
    }
}
