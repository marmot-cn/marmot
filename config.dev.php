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
    //'mongo.host' => 'mongodb://mongo:27017',
    'mongo.host' => 'mongodb://120.25.87.35:27018,120.25.87.35:27017,120.25.87.35:27019',
    'mongo.user' => 'myTester',
    'mongo.password' => 'xyz123',
    'mongo.database' => 'test',
    //cache
    'cache.route.disable' => true,
    //memcached
    'memcached.serevice'=>[['memcached-1',11211],['memcached-2',11211]],
];
