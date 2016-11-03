<?php
namespace Member\Persistence;

use System\Classes\Db;

/**
 * user表数据库层文件
 * @author chloroplast
 * @version 1.0.0: 20160223
 */
class UserDb extends Db
{
    
    public function __construct()
    {
        parent::__construct('user');
    }
}
