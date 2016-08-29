<?php
//清除memcached
\Marmot\Core::$cacheDriver->flushAll();
runSucess('memcached');

//清除apcu
apcu_clear_cache();
runSucess('apcu');