<?php
//清除memcached
Core::$_cacheDriver->flushAll();
runSucess('memcached');

//清除apcu
apcu_clear_cache();
runSucess('apcu');
