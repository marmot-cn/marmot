<?php
/**
 * \d+(,\d+)*
 * 路由设置
 */
return [
    //user 用户
    //获取用户详情接口
    [
        'method'=>'GET',
        'rule'=>'/users[/{ids:[\d,]+}]',
        'controller'=>[
            'User\Controller\UserController',
            'get'
        ]
    ],
];
