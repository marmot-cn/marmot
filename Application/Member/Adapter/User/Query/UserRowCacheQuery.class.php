<?php
namespace Member\Adapter\User\Query;

use Marmot\Framework\Query\RowCacheQuery;

class UserRowCacheQuery extends RowCacheQuery
{
    public function __construct()
    {
        parent::__construct(
            'user_id',
            new Persistence\UserCache(),
            new Persistence\UserDb()
        );
    }
}
