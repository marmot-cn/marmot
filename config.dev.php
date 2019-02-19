<?php
ini_set("display_errors","on");

return [
	//database
    'database.host'     => 'mysql',
    'database.port'     => 3306,
    'database.dbname'   => 'marmot',
    'database.user'		=> 'root',
    'database.password'	=> '123456',
    'database.tablepre' => 'pcore_',
    //mongo
    'mongo.host' => 'mongodb://mongo:27017',
    'mongo.uriOptions' => [
    ],
    'mongo.driverOptions' => [
    ],
    'mongo.user' => 'myTester',
    'mongo.password' => 'xyz123',
    'mongo.database' => 'test',
    //cache
    'cache.route.disable' => true,
    //fluentd
    'fluentd.address' => '',
    'fluentd.port' => '24224',
    'fluentd.tag' => '',
    //memcached
    'memcached.serevice'=>[['memcached-1',11211],['memcached-2',11211]],
];
