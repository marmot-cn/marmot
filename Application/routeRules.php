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
    [
        'method'=>'POST',
        'rule'=>'/users',
        'controller'=>[
            'User\Controller\UserController',
            'post'
        ]
    ],
    [
        'method'=>'PUT',
        'rule'=>'/users/{id:\d+}',
        'controller'=>[
            'User\Controller\UserController',
            'put'
        ]
    ],
    [
        'method'=>'DELETE',
        'rule'=>'/users/{id:\d+}',
        'controller'=>[
            'User\Controller\UserController',
            'delete'
        ]
    ]
];
