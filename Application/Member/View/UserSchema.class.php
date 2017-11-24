<?php
namespace Member\View;

use Neomerx\JsonApi\Schema\SchemaProvider;
use Member\Model\User;

/**
 * @codeCoverageIgnore
 */
class UserSchema extends SchemaProvider
{
    protected $resourceType = 'users';

    public function getId($user) : int
    {
        return $user->getId();
    }

    public function getAttributes($user) : array
    {
        return [
            'cellphone'  => $user->getCellphone(),
            'userName' => $user->getUserName(),
            'realName' => $user->getRealName(),
            'nickName' => $user->getNickName(),
            'status' => $user->getStatus(),
            'createTime' => $user->getCreateTime(),
            'updateTime' => $user->getUpdateTime(),
            'statusTime' => $user->getStatusTime()
        ];
    }
}
