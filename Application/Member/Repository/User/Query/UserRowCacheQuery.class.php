<?php
namespace Member\Repository\User\Query;

use System\Query\RowCacheQuery;

use Member\Persistence\UserCache;
use Member\Persistence\UserDb;

class UserRowCacheQuery extends RowCacheQuery
{

    public function __construct()
    {
        parent::__construct('user_id', new UserCache(), new UserDb());
    }
}
