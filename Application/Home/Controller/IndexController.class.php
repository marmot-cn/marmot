<?php
namespace Home\Controller;

use System\Classes\Controller;
use Marmot\Core;
use Common\Controller\JsonApiController;

use Home\View\NewsView;
use Home\Model\{News, Comment};


//use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Message\AMQPMessage;

class IndexController extends Controller
{   

    use JsonApiController;
    /**
     * @codeCoverageIgnore
     */
    public function index()
    {	

    	//http://www.marmot.com/?filter[test]=1&filter[aa]=2&sort=-created,title&include=comments.author&fields[articles]=title,body&page[num]=1&page[size]=10
        $news = new News(1, 'title');
        $news->setContent('content');

        $comments = array();

        $comments[] = new Comment(1, 'content1');
        $comments[] = new Comment(2, 'content2');

        $news2 = new News(2, 'title2');
        $news2->setContent('content2');

        // $news->setComments($comments);
        
        // $a = [
        //   Comment::class => CommentSchema::class,
        //   News::class => NewsSchema::class,
        // ];

        // $result = $this->jsonApiFormat([$news, $news2], $a, '');

        

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
        // echo '<pre>';
        // echo $result;

        $view = new NewsView([$news, $news2]);
        // $url = 'users/'.http_build_query($gets);

        $this->render($view->pagination($url = 'users', $conditions = $this->getRequest()->get(), $num = 50, $perpage = 10, $curpage = 2));
        // var_dump("Hello World"); 
        return true;
    }
}
