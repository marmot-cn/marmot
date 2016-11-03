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
            'Member\Controller\UserController',
            'get'
        ]
    ],
    //注册
    [
        'method'=>'POST',
        'rule'=>'/users',
        'controller'=>[
            'Member\Controller\UserController',
            'signUp'
        ]
    ],
    //登录
    [
        'method'=>'POST',
        'rule'=>'/users/signIn',
        'controller'=>[
            'Member\Controller\UserController',
            'signIn'
        ]
    ],
    //修改用户密码
    [
        'method'=>'PUT',
        'rule'=>'/users/{id:\d+}/updatePassword',
        'controller'=>[
            'Member\Controller\UserController',
            'updatePassword'
        ]
    ],
];
