<?php
namespace User\Controller;

use System\Classes\Controller;
use Common\Controller\JsonApiController;

class UserController extends Controller
{
   
    use JsonApiController;
    
    public function get(string $ids = '')
    {
        //http://www.marmot.com/users?filter[test]=1&filter[aa]=2&sort=-created,title&include=comments.author&fields[articles]=title,body&page[num]=1&page[size]=10
        echo '<pre>';
        if (!empty($ids)) {
            if (is_numeric($ids)) {//获取单条
                var_dump('single:'.$ids);
            } else {//批量获取
                var_dump('multi:'.$ids);
            }
        } else {//查询
            $parameters = $this->getParameters();
            var_dump('filter:');
            var_dump($parameters);
        }

        // $view = new NewsView([$news, $news2]);
        // $this->render($view->pagination(
        //         $url = 'users',
        //         $conditions = $this->getRequest()->get(),
        //         $num = 50,
        //         $perpage = 10,
        //         $curpage = 2
        //     )
        // );
        return false;
    }
}
