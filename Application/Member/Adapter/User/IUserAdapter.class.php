<?php
namespace Member\Adapter\User;

use Member\Model\User;

interface IUserAdapter
{
    public function add(User $user) : bool;

    public function update(User $user, array $keys = array()) : bool;

    public function getOne($id) : User;

    public function getList(array $ids) : array;

    public function filter(
        array $filter = array(),
        array $sort = array(),
        int $offset = 0,
        int $size = 0
    ) : array;
}
