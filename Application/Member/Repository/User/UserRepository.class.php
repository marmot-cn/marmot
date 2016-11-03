<?php
namespace Member\Repository\User;

use Member\Repository\User\Query\UserRowCacheQuery;
use Member\Model\User;
use Member\Translator\UserTranslator;
use Marmot\Core;

/**
 * 用户仓库
 *
 * @author chloroplast
 * @version 1.0:20160227
 */
class UserRepository
{

    /**
     * @var UserRowCacheQuery $userRowCacheQuery 行缓存
     */
    private $userRowCacheQuery;

    /**
     * @var System\Classes\Translator $translator 翻译器
     */
    private $translator;

    public function __construct(UserRowCacheQuery $userRowCacheQuery)
    {
        $this->userRowCacheQuery = $userRowCacheQuery;
        $this->translator = new UserTranslator();
    }

    public function add(User $user)
    {
        $info = array();
        //list
        $info = $this->translator->objectToArray($user);
        $id = $this->userRowCacheQuery->add($info);
        if (!$id) {
            return false;
        }

        $user->setId($id);
        return true;
    }

    public function update(User $user, array $keys = array())
    {
    
        $info = array();

        $conditionArray[$this->userRowCacheQuery->getPrimaryKey()] = $user->getId();

        $info = $this->translator->objectToArray($user, $keys);

        $result = $this->userRowCacheQuery->update($info, $conditionArray);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 获取用户
     * @param integer $id 用户id
     */
    public function getOne($id)
    {
        $info = array();
        //获取用户数据
        $info = $this->userRowCacheQuery->getOne($id);
        if (empty($info)) {
            return false;
        }
        //返回翻译过的对象
        return $this->translator->arrayToObject($info);
    }

    /**
     * 批量获取用户
     * @param array $ids 商户申请表id数组
     */
    public function getList(array $ids)
    {

        $userList = array();
        //获取用户数据
        $userInfoList = $this->userRowCacheQuery->getList($ids);
        
        foreach ($userInfoList as $userInfo) {
            $userList[] = $this->translator->arrayToObject($userInfo);
        }
        
        return $userList;
    }

    /**
     * 根据条件查询用户
     */
    public function filter(
        array $filter = array(),
        array $sort = array(),
        int $offset = 0,
        int $size = 20
    ) {

        $conjection = $condition = '';

        $user = new User();

        //拼接filter变量
        if (isset($filter['cellPhone'])) {
            $user->setCellPhone($filter['cellPhone']);
            $info = $this->translator->objectToArray($user, array('cellPhone'));
            $condition .= $conjection.key($info).' = \''.current($info).'\'';
            $conjection = ' AND ';
        }
        if (isset($filter['password'])) {
            $user->setPassword($filter['password']);
            $info = $this->translator->objectToArray($user, array('password'));
            $condition .= $conjection.key($info).' = \''.current($info).'\'';
            $conjection = ' AND ';
        }
        if (isset($filter['status'])) {
            $user->setStatus($filter['status']);
            $info = $this->translator->objectToArray($user, array('status'));
            $condition .= $conjection.key($info).' = '.current($info);
            $conjection = ' AND ';
        }
        //查询数据
        $list = $this->userRowCacheQuery->find($condition, $offset, $size);
        if (empty($list)) {
            return false;
        }

        $ids = array();
        foreach ($list as $info) {
            $ids[] = $info[$this->userRowCacheQuery->getPrimaryKey()];
        }

        //计算总数
        $count = 0;
        //如果返回数据总数超过每页的分页数,
        //我们需要查询总数,
        //否则我们返回该数量
        $idsCount = sizeof($ids);
        if (($idsCount) >= $size) {
            $count = $this->userRowCacheQuery->count($condition);
        } else {
            $count = $idsCount;
        }

        return array($this->getList($ids), $count);
    }
}
