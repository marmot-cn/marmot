<?php

return [
    //core class
    'System\Classes\Cache' => S_ROOT.'System/Classes/Cache.class.php',
    'System\Classes\CommandBus' => S_ROOT.'System/Classes/CommandBus.class.php',
    'System\Classes\Db'     => S_ROOT.'System/Classes/Db.class.php',
    'System\Classes\MyPdo' => S_ROOT.'System/Classes/MyPdo.class.php',
    'System\Classes\Transaction' => S_ROOT.'System/Classes/Transaction.class.php',
    'System\Classes\Controller' => S_ROOT.'System/Classes/Controller.class.php',
    'System\Classes\Filter' => S_ROOT.'System/Classes/Filter.class.php',
    'System\Classes\Request' => S_ROOT.'System/Classes/Request.class.php',
    'System\Classes\Response' => S_ROOT.'System/Classes/Response.class.php',
    'System\Classes\Error' => S_ROOT.'System/Classes/Error.class.php',
    'System\Classes\Event' => S_ROOT.'System/Classes/Event.class.php',
    'System\Classes\Server' => S_ROOT.'System/Classes/Server.class.php',
    'System\Classes\ValidateStrategy' => S_ROOT.'System/Classes/ValidateStrategy.class.php',

    //strategy
    'System\Strategy\Validate\IntStrategy' => S_ROOT.'System/Strategy/Validate/IntStrategy.class.php',
    'System\Strategy\Validate\FloatStrategy' => S_ROOT.'System/Strategy/Validate/FloatStrategy.class.php',
    'System\Strategy\Validate\StringStrategy' => S_ROOT.'System/Strategy/Validate/StringStrategy.class.php',
    'System\Strategy\Validate\DateStrategy' => S_ROOT.'System/Strategy/Validate/DateStrategy.class.php',
    'System\Strategy\Validate\EmailStrategy' => S_ROOT.'System/Strategy/Validate/EmailStrategy.class.php',
    'System\Strategy\MediaTypes\JsonapiStrategy' => S_ROOT.'System/Strategy/MediaTypes/JsonApiStrategy.class.php',

    //interfaces
    'System\Interfaces\Command' => S_ROOT.'System/Interfaces/Command.class.php',
    'System\Interfaces\Observer' => S_ROOT.'System/Interfaces/Observer.class.php',
    'System\Interfaces\Subject' => S_ROOT.'System/Interfaces/Subject.class.php',
    'System\Interfaces\Widget' => S_ROOT.'System/Interfaces/Widget.class.php',
    'System\Interfaces\CacheLayer' => S_ROOT.'System/Interfaces/CacheLayer.class.php',
    'System\Interfaces\DbLayer' => S_ROOT.'System/Interfaces/DbLayer.class.php',
    'System\Interfaces\IView' => S_ROOT.'System/Interfaces/IView.class.php',
    'System\Interfaces\ICommand' => S_ROOT.'System/Interfaces/ICommand.class.php',
    'System\Interfaces\ICommandHandler' => S_ROOT.'System/Interfaces/ICommandHandler.class.php',
    'System\Interfaces\ICommandHandlerFactory' => S_ROOT.'System/Interfaces/ICommandHandlerFactory.class.php',
    'System\Interfaces\IResponseFormatter' => S_ROOT.'System/Interfaces/IResponseFormatter.class.php',
    'System\Interfaces\IValidateStrategy' => S_ROOT.'System/Interfaces/IValidateStrategy.class.php',
    'System\Interfaces\IMediaTypeStrategy' => S_ROOT.'System/Interfaces/IMediaTypeStrategy.class.php',
    'System\Interfaces\ITranslator' => S_ROOT.'System/Interfaces/ITranslator.class.php',

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
    'System\Query\DBVectorQuery' => S_ROOT.'System/Query/DBVectorQuery.class.php',
    'System\Query\SearchQuery' => S_ROOT.'System/Query/SearchQuery.class.php',
    'System\Query\DataCacheQuery' => S_ROOT.'System/Query/DataCacheQuery.class.php',
    'System\Query\RowQueryFindable' => S_ROOT.'System/Query/RowQueryFindable.class.php',

    //view
    'System\View\JsonApiResponseFormatter' => S_ROOT.'System/View/JsonApiResponseFormatter.class.php',
    'System\View\EmptyView' => S_ROOT.'System/View/EmptyView.class.php',
    'System\View\ErrorView' => S_ROOT.'System/View/ErrorView.class.php'
];
