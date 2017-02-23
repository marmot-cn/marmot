<?php
namespace Member\Controller;

use Member\Model\User;
use System\Classes\Controller;
use Common\Controller\JsonApiController;
use Member\View\UserView;
use System\View\EmptyView;
use System\Classes\CommandBus;
use System\Interfaces\ICommand;
use Marmot\Core;

use Member\Command\User\SignUpUserCommand;
use Member\Command\User\UpdatePasswordUserCommand;
use Member\CommandHandler\User\UserCommandHandlerFactory;

class UserController extends Controller
{
    use JsonApiController;

    /**
     * 对应路由 /users[/{ids:[\d,]+}]
     * GET方法传参
     * 根据用户id获取用户详情,该接口用于:
     * 1. 获取单个用户详情数据
     * 2. 批量多个用户详情数据
     * 3. 根据检索条件获取用户详情数据
     *
     * 示例: /users/1,2,3 获取用户id为1的信息
     * /users?page[number]=5&page[size]=20 从第5页开始取数据,每页取20条
     *
     * @param int $id 用户id
     * @return jsonApi
     */
    public function get(string $ids = '')
    {
        //初始化仓库
        $repository = Core::$container->get('Member\Repository\User\UserRepository');

        if (!empty($ids)) {
            if (is_numeric($ids)) {//获取单条
                $user = $repository->getOne($ids);
                if ($user instanceof User) {
                    $this->render(new UserView($user));
                    return true;
                }
            }

            //批量获取
            $userList = $repository->getList(explode(',', $ids));
            if (!empty($userList)) {
                $this->render(new UserView($userList));
                return true;
            }
        }

        list($filter, $sort, $curpage, $perpage) = $this->formatParameters();

        //过滤参数
        list($userList, $count) = $repository->filter(
            $filter,
            $sort,
            ($curpage-1)*$perpage,
            $perpage
        );

        if ($count > 0) {
            //获取多条数据 repository->filter 返回 list 和 count
            $view = new UserView($userList);
            $this->render(
                $view->pagination(
                    'users',
                    $this->getRequest()->get(),
                    $count,
                    $perpage,
                    $curpage
                )
            );
            return true;
        }

        $this->getResponse()->setStatusCode(204);
        $this->render(new EmptyView());
        return false;
    }

    /**
     * 对应路由 /users
     * 用户注册功能,通过post传参
     * @param jsonApi array("data"=>array("type"=>"users",
     *                                    "attributes"=>array("cellPhone"=>"手机号",
     *                                                        "password"=>"密码"
     *                                                        )
     *                                    )
     *                      )
     * @return jsonApi
     */
    public function signUp()
    {
        $data = $this->getRequest()->post('data');

        if ($data['type'] == 'users') {
            $commandBus = new CommandBus(new UserCommandHandlerFactory());

            if (!empty($data['attributes']['cellPhone']) && !empty($data['attributes']['password'])) {
                $command = new SignUpUserCommand(
                    $data['attributes']['cellPhone'],
                    $data['attributes']['password']
                );
            }

            if ($command instanceof ICommand) {
                if ($commandBus->send($command)) {
                    //查询新注册的用户
                    $repository = Core::$container->get('Member\Repository\User\UserRepository');
                    $user = $repository->getOne($command->uid);
                    if ($user instanceof User) {
                        $this->getResponse()->setStatusCode(201);
                        $this->render(new UserView($user));
                        return true;
                    }
                    //返回新用户信息
                }
            }
        }

        $this->getResponse()->setStatusCode(409);
        $this->render(new EmptyView());
        return false;
    }

    /**
     * /users/signIn
     * 用户登录功能,通过POST传参
     * @param jsonApi array("data"=>array("type"=>"users",
     *                                    "attributes"=>array("cellPhone"=>"手机号",
     *                                                        "password"=>"密码"
     *                                                        )
     *                                   )
     *                         )
     * @return jsonApi
     */
    public function signIn()
    {
        $data = $this->getRequest()->post('data');

        if ($data['type'] == 'users') {
            if (!empty($data['attributes']['cellPhone']) && !empty($data['attributes']['password'])) {
                $repository = Core::$container->get('Member\Repository\User\UserRepository');
                list($userList, $count) = $repository->filter(
                    array(
                        'cellPhone' => $data['attributes']['cellPhone']
                    )
                );

                if ($count == 1) {
                    $loginUser = $userList[0];

                    if ($loginUser instanceof User) {
                        //根据检索出的用户盐,和传递过来的密码 加密用户
                        $user = new User();
                        $user->encryptPassword(
                            $data['attributes']['password'],
                            $loginUser->getSalt()
                        );

                        if ($loginUser->getPassword() == $user->getPassword()) {
                            $this->render(new UserView($loginUser));
                            return true;
                        }
                    }
                }
            }
        }

        $this->getResponse()->setStatusCode(404);
        $this->render(new EmptyView());
        return false;
    }

     /**
     * 对应路由 /users/{id:\d+}/updatePassword
     * 更新用户密码,通过PUT传参,json
     * @param string id 用户id
     * @param jsonApi array("data"=>array("type"=>"users",
     *                                    "attributes"=>array("oldPassword"=>"旧密码",
     *                                                        "password"=>"新密码"
     *                                                       )
     *                                   )
     *                         )
     * @return jsonApi
     */
    public function updatePassword($id)
    {
        $data = $this->getRequest()->put("data");
       
        if ($data['type'] == 'users') {
            if (!empty($data['attributes']['oldPassword']) && !empty($data['attributes']['password'])) {
                $commandBus = new CommandBus(new UserCommandHandlerFactory());
                if ($commandBus->send(new UpdatePasswordUserCommand(
                    $data['attributes']['oldPassword'],
                    $data['attributes']['password'],
                    $id
                ))
                ) {
                    $repository = Core::$container->get('Member\Repository\User\UserRepository');
                    $user  = $repository->getOne($id);

                    if ($user instanceof User) {
                        $this->render(new UserView($user));
                        return true;
                    }
                }
            }
        }

        $this->getResponse()->setStatusCode(404);
        $this->render(new EmptyView());
        return false;
    }
}
