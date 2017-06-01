<?php
//powered by kevin
namespace System\Classes;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Marmot\Core;

/**
 * 存储事件源
 */
class Event
{
    private $source;
    private $sourceName;
    private $eventName;

    public function __construct(string $sourceName, $source, string $eventName)
    {
        $this->source = $source;
        $this->sourceName = $sourceName;
        $this->eventName = $eventName;
    }

    public function save()
    {
        $eventDb = new class extends Db {
            public function __construct()
            {
                parent::__construct('event_store');
            }
        };
        $lastetEventId = $eventDb->insert(
            array(
                'source' => $this->sourceName,
                'source_id' => $this->source->getId(),
                'event_name' => $this->eventName,
                'create_time' => time()
            ),
            true
        );

        if (!$lastetEventId) {
            return false;
        }

        return true;
    }

    public function notify()
    {
        //获取配置信息
        $url = Core::$container->get('rabbitmq.url');
        $port = Core::$container->get('rabbitmq.port');
        $user = Core::$container->get('rabbitmq.user');
        $password = Core::$container->get('rabbitmq.password');

        $connection = new AMQPStreamConnection($url, $port, $user, $password);
        $channel = $connection->channel();

        $channel->exchange_declare('event', 'fanout', false, false, false);

        $data = $this->sourceName.':'.$this->source->getId().':'.$this->eventName;
        $msg = new AMQPMessage($data, array('delivery_mode' => 2));

        $channel->basic_publish($msg, 'event');
        $channel->close();
        $connection->close();

        return true;
    }
}
