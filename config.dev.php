<?php
return [
	//database
    'database.host'     => 'mysql',
    'database.port'     => 3306,
    'database.dbname'   => 'marmot',
    'database.user'		=> 'root',
    'database.passwod'	=> '123456',
    'database.tablepre' => 'pcore_',
    //mongo
    'mongo.host' => 'mongodb://mongo:27017',
    //cache
    'cache.route.disable' => true,
    //memcached
    'memcached.serevice'=>[['memcached-1',11211],['memcached-2',11211]],
];
