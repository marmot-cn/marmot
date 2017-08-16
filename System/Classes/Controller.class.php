<?php
//powered by chloroplast
namespace System\Classes;

use Marmot\Core;
use System\Interfaces\IView;

/**
 * 应用层服务父类,控制应用服务层的 Request 和 Reponse
 */
abstract class Controller
{

    /**
     * @var Request $request 请求对象
     */
    private $request;

    /**
     * @var Response $response 响应对象
     */
    private $response;

    /**
     * 构造函数,请求初始化 请求对象 和 响应对象
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        unset($this->request);
        unset($this->response);
    }

    /**
     * 获取 request 对象
     */
    public function getRequest() : Request
    {
        return $this->request;
    }

    /**
     * 获取 response 对象
     */
    public function getResponse() : Response
    {
        return $this->response;
    }

    /**
     * 渲染输出内容
     * @var array|string 输出源内容
     */
    public function render(IView $iview)
    {
        $this->getResponse()->data = $iview->display();
        return $this->getResponse()->send();
    }

    /**
     * 验证应用服务层参数
     * @return bool
     */
    protected function validate(array $rules) : bool
    {
        foreach ($rules as $verifyValue => $rule) {
            list($stragetyName, $options, $errorCode) = $rule;
            $strategyName = ucfirst($strategyName);
            
            if (!$this->isStrategyExist($strategyName)) {
                return false;
            }

            $strategy = new $strategyName();
            if (!$strategy->verify($verifyValue, $options, $errorCode)) {
                return false;
            }
            return true;
        }

        return true;
    }

    private function isStrategyExist(string $strategy) : bool
    {
        return $this->isSystemStrategyExist($strategy)
                && $this->isApplicationStrategyExist($strategy);
    }

    private function isSystemStrategyExist(string $strategy) : bool
    {
        if (!class_exists($strategy)) {
            //错误code
            return false;
        }

        return true;
    }

    private function isApplicationStrategyExist(string $strategy) : bool
    {
        if (!class_exists('Application/Strategy/'.$stragety)) {
            //错误code
            return false;
        }

        return true;
    }
}
