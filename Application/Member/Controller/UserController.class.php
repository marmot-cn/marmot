<?php
namespace Member\Controller;

use System\Classes\Controller;
use System\Interfaces\INull;
use System\Classes\CommandBus;
use System\Interfaces\ICommand;

use Application\WidgetRules;
use Common\Controller\JsonApiController;

use Member\Model\User;
use Member\View\UserView;
use Member\Command\User\SignUpUserCommand;
use Member\Command\User\UpdatePasswordUserCommand;
use Member\CommandHandler\User\UserCommandHandlerFactory;
use Member\Repository\User\UserRepository;

class UserController extends Controller
{
    use JsonApiController;

    private $userRepository;

    private $commandBus;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
        $this->commandBus = new CommandBus(new UserCommandHandlerFactory());
    }

    protected function getUserRepository() : UserRepository
    {
        return $this->userRepository;
    }

    protected function getCommandBus() : CommandBus
    {
        return $this->commandBus;
    }

    public function getOne(int $id)
    {
        if ($this->validateGetOneScenario($id)) {
            $repository = $this->getUserRepository();

            $user = $repository->getOne($id);
            if (!$user instanceof INull) {
                $this->render(new UserView($user));
                return true;
            }
        }

        $this->displayError();
        return false;
    }

    private function validateGetOneScenario(int $id)
    {
        return $this->getRequest()->validate([WidgetRules::id($id, 6)]);
    }

    public function getList(string $ids)
    {
        $repository = $this->getUserRepository();

        //批量获取
        $userList = $repository->getList(explode(',', $ids));
        if (!empty($userList)) {
            $this->render(new UserView($userList));
            return true;
        }

        $this->displayError();
        return false;
    }

    public function filter()
    {
        $repository = $this->getUserRepository();

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
            $view->pagination(
                'users',
                $this->getRequest()->get(),
                $count,
                $perpage,
                $curpage
            );
            $this->render($view);
            return true;
        }

        $this->displayError();
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

        $this->displayError();
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

        $this->displayError();
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

        $type = $data['type'];
        $oldPassword = $data['attributes']['oldPassword'];
        $password = $data['attributes']['password'];
       
        if ($type == 'users') {
            if (!empty($oldPassword) && !empty($password)) {
                $commandBus = $this->getCommandBus();
                if ($commandBus->send(
                    new UpdatePasswordUserCommand(
                        $oldPassword,
                        $password,
                        $id
                    )
                )
                ) {
                    $repository = $this->getUserRepository();
                    $user  = $repository->getOne($id);

                    if (!$user instanceof INull) {
                        $this->render(new UserView($user));
                        return true;
                    }
                }
            }
        }

        $this->displayError();
        return false;
    }
}
