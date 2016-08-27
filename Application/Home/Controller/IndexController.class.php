<?php
namespace Home\Controller;

use System\Classes\Controller;

use Home\Model\News;
use Home\View\NewsSchema;
use Home\Model\Comment;
use Home\View\CommentSchema;

use Neomerx\JsonApi\Http\Request;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Factories\Factory;
//use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Message\AMQPMessage;

class IndexController extends Controller
{
    /**
     * @codeCoverageIgnore
     */
    public function index()
    {	
    	//http://www.marmot.com/?filter[test]=1&filter[aa]=2&sort=-created,title&include=comments.author
    	$nonPsr7request = $this->getRequest();

    	$psr7request  = new Request(function() use ($nonPsr7request) { 
    		return $nonPsr7request->getMethod();
    	}, function ($name) use ($nonPsr7request) {
		    return $nonPsr7request->getHeader($name);
		}, function () use ($nonPsr7request) {
		    return $nonPsr7request->getQueryParams();
		});

    	$factory    = new Factory();
		$parameters = $factory->createQueryParametersParser()->parse($psr7request);
		echo '<pre>';
		var_export($parameters);
		exit();

        $news = new News(1, 'title');
        $news->setContent('content');

        $comments = array();

        $comments[] = new Comment(1, 'content1');
        $comments[] = new Comment(2, 'content2');

        $news->setComments($comments);
        
        $encoder = Encoder::instance([
        	Comment::class => CommentSchema::class,
            News::class => NewsSchema::class,
        ], new EncoderOptions(JSON_PRETTY_PRINT, 'http://example.com/api/v1'));

        $result = $encoder->encodeData($news);

//        $exchange = 'router';
//        $queue = 'msgs';
//        $connection = new AMQPStreamConnection('120.25.87.35', 5672, 'guest', 'guest', '/');
//        $channel = $connection->channel();
//
//        $channel->queue_declare('hello', false, false, false, false);
//
//        $time = date('Y-m-d H:i:s', time());
//        $msg = new AMQPMessage($time);
//        $channel->basic_publish($msg, '', 'hello');
//
//        echo " [x] Sent ".$time."\n";
//        $channel->close();
//        $connection->close();
        echo '<pre>';
        echo $result;
        return true;
    }
}
