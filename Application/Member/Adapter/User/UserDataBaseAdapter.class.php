<?php
namespace Member\Adapter\User;

use Member\Translator\UserDataBaseTranslator;
use Member\Model\User;
use Member\Adapter\User\Query\UserRowCacheQuery;

class UserDataBaseAdapter implements IUserAdapter
{
    private $userDataBaseTranslator;
    private $userRowCacheQuery;

    public function __construct()
    {
        $this->userDataBaseTranslator = new UserDataBaseTranslator();
        $this->userRowCacheQuery = new UserRowCacheQuery();
    }

    public function __destruct()
    {
        unset($this->userDataBaseAdapter);
        unset($this->userRowCacheQuery);
    }
    
    public function setUserDataBaseTranslator(UserDataBaseTranslator $userDataBaseTranslator)
    {
        $this->userDataBaseTranslator = $userDataBaseTranslator;
    }

    private function getUserDataBaseTranslator() : UserDataBaseTranslator
    {
        return $this->userDataBaseTranslator;
    }

    public function setUserRowCacheQuery(UserRowCacheQuery $userRowCacheQuery)
    {
        $this->userRowCacheQuery = $userRowCacheQuery;
    }

    private function getUserRowCacheQuery() : UserRowCacheQuery
    {
        return $this->userRowCacheQuery;
    }
    
    public function add(User $user) : bool
    {
        $info = array();

        $info = $this->getUserDataBaseTranslator()->objectToArray($user);
        $id = $this->getUserRowCacheQuery()->add($info);
        if (!$id) {
            return false;
        }

        $user->setId($id);
        return true;
    }

    public function update(User $user, array $keys = array()) : bool
    {
        $info = array();

        $conditionArray[$this->getUserRowCacheQuery()->getPrimaryKey()] = $user->getId();

        $info = $this->getUserDataBaseTranslator()->objectToArray($user, $keys);

        $result = $this->getUserRowCacheQuery()->update($info, $conditionArray);
        if (!$result) {
            return false;
        }

        return true;
    }

    public function getOne($id)
    {
        $info = array();

        $info = $this->getUserRowCacheQuery()->getOne($id);
        if (empty($info)) {
            return false;
        }

        return $this->getUserDataBaseTranslator()->arrayToObject($info);
    }

    public function getList(array $ids)
    {
        $userList = array();
        
        $userInfoList = $this->getUserRowCacheQuery()->getList($ids);
        if (empty($userInfoList)) {
            return false;
        }

        foreach ($userInfoList as $userInfo) {
            $userList[] = $this->getUserDataBaseTranslator()->arrayToObject($userInfo);
        }
        return $userList;
    }

    private function formatFilter(array $filter)
    {
        $condition = $conjection = '';

        if (!empty($filter)) {
            $user = new User();

            if (isset($filter['cellPhone'])) {
                $user->setCellPhone($filter['cellPhone']);
                $info = $this->getUserDataBaseTranslator()->objectToArray($user, array('cellPhone'));
                $condition .= $conjection.key($info).' = \''.current($info).'\'';
                $conjection = ' AND ';
            }
            if (isset($filter['password'])) {
                $user->setPassword($filter['password']);
                $info = $this->getUserDataBaseTranslator()->objectToArray($user, array('password'));
                $condition .= $conjection.key($info).' = \''.current($info).'\'';
                $conjection = ' AND ';
            }
            if (isset($filter['status'])) {
                $user->setStatus($filter['status']);
                $info = $this->getUserDataBaseTranslator()->objectToArray($user, array('status'));
                $condition .= $conjection.key($info).' = '.current($info);
                $conjection = ' AND ';
            }
        }

        return empty($condition) ? ' 1 ' : $condition ;
    }

    private function formatSort(array $sort)
    {
        $condition = '';
        $conjection = ' ORDER BY ';

        if (!empty($sort)) {
            if (isset($sort['id'])) {
                $info = $this->getUserDataBaseTranslator()->objectToArray(new User(), array('id'));
                $condition .= $conjection.key($info).' '.($sort['id'] == -1 ? 'DESC' : 'ASC');
                $conjection = ',';
            }
        }

        return $condition;
    }

    public function filter(
        array $filter = array(),
        array $sort = array(),
        int $offset = 0,
        int $size = 20
    ) {
        $condition = $this->formatFilter($filter);
        $condition .= $this->formatSort($sort);

        $list = $this->userRowCacheQuery->find($condition, $offset, $size);
        if (empty($list)) {
            return false;
        }

        $ids = array();
        foreach ($list as $info) {
            $ids[] = $info[$this->userRowCacheQuery->getPrimaryKey()];
        }

        $count = 0;

        $count = sizeof($ids);
        if ($count  == $size || $offset > 0) {
            $count = $this->userRowCacheQuery->count($condition);
        }

        return array($this->getList($ids), $count);
    }
}
