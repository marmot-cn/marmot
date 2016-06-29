<?php

return [
    //core class
    'System\Classes\Cache' => S_ROOT.'System/Classes/Cache.class.php',
    'System\Classes\Db'     => S_ROOT.'System/Classes/Db.class.php',
    'System\Classes\MyPdo' => S_ROOT.'System/Classes/MyPdo.class.php',
    'System\Classes\Transaction' => S_ROOT.'System/Classes/Transaction.class.php',
    
    //interfaces
    'System\Interfaces\Command' => S_ROOT.'System/Interfaces/Command.class.php',
    'System\Interfaces\Observer' => S_ROOT.'System/Interfaces/Observer.class.php',
    'System\Interfaces\Pcommand' => S_ROOT.'System/Interfaces/Pcommand.class.php',
    'System\Interfaces\Subject' => S_ROOT.'System/Interfaces/Subject.class.php',
    'System\Interfaces\Widget' => S_ROOT.'System/Interfaces/Widget.class.php',
    'System\Interfaces\CacheLayer' => S_ROOT.'System/Interfaces/CacheLayer.class.php',
    'System\Interfaces\DbLayer' => S_ROOT.'System/Interfaces/DbLayer.class.php',

    //command
    'System\Command\Cache\SaveCacheCommand' => S_ROOT.'System/Command/Cache/SaveCacheCommand.class.php',
    'System\Command\Cache\DelCacheCommand' => S_ROOT.'System/Command/Cache/DelCacheCommand.class.php',

    //core observer
    'System\Observer\CacheObserver' => S_ROOT.'System/Observer/CacheObserver.class.php',
    'System\Observer\Subject' => S_ROOT.'System/Observer/Subject.class.php',

    //Query
    'System\Query\RowCacheQuery' => S_ROOT.'System/Query/RowCacheQuery.class.php',
    'System\Query\RowQuery' => S_ROOT.'System/Query/RowQuery.class.php',
    'System\Query\FragmentCacheQuery' => S_ROOT.'System/Query/FragmentCacheQuery.class.php',
    'System\Query\VectorQuery' => S_ROOT.'System/Query/VectorQuery.class.php',
    'System\Query\SearchQuery' => S_ROOT.'System/Query/SearchQuery.class.php',
];
