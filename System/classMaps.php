<?php

return [
	//core class
	'System\Core\Db'	=> S_ROOT.'/System/Core/Db.class.php',
	'System\Core\String' => S_ROOT.'/System/Core/String.class.php',
	'System\Core\MyPdo' => S_ROOT.'/System/Core/MyPdo.class.php',
	'System\Core\Cache' => S_ROOT.'/System/Core/Cache/Cache.class.php',
	'System\Core\Transaction' => S_ROOT.'/System/Core/Transaction.class.php',
	'System\Core\Object' => S_ROOT.'/System/Core/Object.class.php',
	
	//command
	'System\Command\Cache\AddCacheCommand' => S_ROOT.'/System/Command/Cache/AddCacheCommand.class.php',
	'System\Command\Cache\DelCacheCommand' => S_ROOT.'/System/Command/Cache/DelCacheCommand.class.php',
	'System\Command\Cache\GetCacheCommand' => S_ROOT.'/System/Command/Cache/GetCacheCommand.class.php',
	'System\Command\Cache\GetListCacheCommand' => S_ROOT.'/System/Command/Cache/GetListCacheCommand.class.php',
	'System\Command\Cache\ReplaceCacheCommand' => S_ROOT.'/System/Command/Cache/ReplaceCacheCommand.class.php',

	//interface
	'System\Interface\Command' => S_ROOT.'/System/Interface/Command.class.php',
	'System\Interface\Observer' => S_ROOT.'/System/Interface/Observer.class.php',
	'System\Interface\Pcommand' => S_ROOT.'/System/Interface/Pcommand.class.php',
	'System\Interface\Subject' => S_ROOT.'/System/Interface/Subject.class.php',
	'System\Interface\Widget' => S_ROOT.'/System/Interface/Widget.class.php',

	//core persistence
	'System\Persistence\SystemFileCache' => S_ROOT.'/System/Persistence/SystemFileCache.class.php',
	'System\Persistence\SystemFileDb' => S_ROOT.'/System/Persistence/SystemFileDb.class.php',

	//core observer
	'System\Observer\Observer' => S_ROOT.'/System/Interface/Observer.class.php'
];

?>