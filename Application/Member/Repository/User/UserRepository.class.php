<?php
namespace Member\Repository\User;

use Member\Adapter\User\UserDataBaseAdapter;
use Member\Adapter\User\IUserAdapter;
use Member\Model\User;
use Marmot\Core;

/**
 * 用户仓库
 *
 * @author chloroplast
 * @version 1.0:20160227
 */
class UserRepository implements IUserAdapter
{
    private $adapter;
    
    public function __construct()
    {
        $this->adapter = new UserDataBaseAdapter();
    }

    protected function getAdapter() : IUserAdapter
    {
        return $this->adapter;
    }

    public function add(User $user) : bool
    {
        return $this->getAdapter()->add($user);
    }

    public function update(User $user, array $keys = array()) : bool
    {
        return $this->getAdapter()->update($user, $keys);
    }

    /**
     * 获取用户
     * @param integer $id 用户id
     */
    public function getOne($id) : User
    {
        return $this->getAdapter()->getOne($id);
    }

    /**
     * 批量获取用户
     * @param array $ids 商户申请表id数组
     */
    public function getList(array $ids) : array
    {
        return $this->getAdapter()->getList($ids);
    }

    /**
     * 根据条件查询用户
     */
    public function filter(
        array $filter = array(),
        array $sort = array(),
        int $offset = 0,
        int $size = 20
    ) : array {
        return $this->getAdapter()->filter($filter, $sort, $offset, $size);
    }
}
