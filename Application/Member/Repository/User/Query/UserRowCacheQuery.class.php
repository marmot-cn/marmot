<?php
namespace Member\Repository\User\Query;

use System\Query\RowCacheQuery;
use Member\Persistence;

class UserRowCacheQuery extends RowCacheQuery
{

    /**
     * @var string $primaryKey 查询数据的键
     */
    private $primaryKey = 'user_id';

    /**
     * @var Persistence\UserCache $cacheLayer
     */
    private $cacheLayer;//缓存层

    /**
     * @var Persistence\UserDb $dbLayer
     */
    private $dbLayer;//数据层

    public function __construct(Persistence\UserCache $cacheLayer, Persistence\UserDb $dbLayer)
    {
        $this->dbLayer = $dbLayer;
        $this->cacheLayer = $cacheLayer;
        parent::__construct($this->primaryKey, $this->cacheLayer, $this->dbLayer);
    }
}
