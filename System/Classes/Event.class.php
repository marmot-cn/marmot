<?php
//powered by kevin
namespace System\Classes;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Marmot\Core;
use Marmot\Common\IObject;

/**
 * 事件源
 */
class Event
{
    private $source;
    private $sourceName;
    private $eventName;

    public function __construct(string $sourceName, IObject $source, string $eventName)
    {
        $this->source = $source;
        $this->sourceName = $sourceName;
        $this->eventName = $eventName;
    }

    public function __destruct()
    {
        unset($this->source);
        unset($this->sourceName);
        unset($this->eventName);
    }

    public function save()
    {
        return $this->saveDb($this->formatSaveEventData());
    }

    private function toArray() : array
    {
        return array(
            'source' => $this->sourceName,
            'source_id' => $this->source->getId(),
            'event_name' => $this->eventName,
            'create_time' => time()
        );
    }

    private function saveDb(array $eventData) : bool
    {
        $eventDb = new class extends Db {
            public function __construct()
            {
                parent::__construct('event_store');
            }
        };

        $lastetEventId = $eventDb->insert($eventData, true);

        return $lastetEventId == 0;
    }

    public function notify() : bool
    {
        return $this->publish($this->formatPublishEventData());
    }

    private function toString() : string
    {
        return $this->sourceName.':'.$this->source->getId().':'.$this->eventName;
    }

    private function publish(string $eventData) : bool
    {
        //获取配置信息
        $url = Core::$container->get('rabbitmq.url');
        $port = Core::$container->get('rabbitmq.port');
        $user = Core::$container->get('rabbitmq.user');
        $password = Core::$container->get('rabbitmq.password');

        $connection = new AMQPStreamConnection($url, $port, $user, $password);
        $channel = $connection->channel();

        $channel->exchange_declare('event', 'fanout', false, false, false);
        
        $message = new AMQPMessage($eventData, array('delivery_mode' => 2));

        $channel->basic_publish($message, 'event');
        $channel->close();
        $connection->close();

        return true;
    }
}
