<?php

return [
	//database
    'database.host'     => 'dbw',
    // 'database.port'     => 5000,
    'database.dbname'   => 'test',
    'database.user'		=> 'root',
    'database.passwod'	=> '123456',
    'database.tablepre' => 'pcore_',
    //cookie
    'cookie.domain'		=>	'',
    'cookie.path'		=>	'/',
    //memcached
    'memcached.serevice'=>[['memcached_1',11211],['memcached_2',11211]] 
];
?>