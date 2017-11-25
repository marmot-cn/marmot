<?php
//清除memcached
$core::$cacheDriver->flushAll();
runSucess('memcached');

