<?php
/**
 * core 核心文件
 *
 * @author  chloroplast1983
 * @version 1.0.20131007
 */

namespace Marmot;

use Marmot\Framework\Classes\Error;
use Marmot\Framework\MarmotCore;

define('APP_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 * 文件核心类
 *
 * @author  chloroplast1983
 * @version 1.0.20130916
 */
class Core extends MarmotCore
{
    
    private static $instance;

    /**
     * 使用单例封装全局函数的core调用
     */
    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 单元测试专用启动路程,用于引导phpunit,bootstrap的路由文件进入.
     * 在这里我们要实现如下功能:
     * 1. 自动加载
     * 2. 初始化容器
     * 3. 初始化缓存
     * 4. 初始化测试持久层存储,用于测试数据库和程序分离
     */
    public function initTest()
    {
        self::initAutoload();//autoload
        self::initTestEnv();//初始化测试环境
        self::initContainer();//引入容器
        self::initEnv();//初始化环境
        self::initCache();//初始化缓存使用
        self::initDb();//初始化mysql
        self::initMongo();
        self::initError();
    }
    
    /**
     * 自动加载方法,这里分为2个部分.这里的借鉴了yii框架的自动加载
     * 符合PSR4自动加载规范
     *
     * 1. 加载第三方的autoload,主要是composer管理的第三方依赖
     * 2. 核心框架自己的autoload
     *    2.1 核心文件主要是通过映射关系载入的,原来使用的是淘宝的一套开源的autoload,
     *        但是考虑其功能过于繁重,这里改为用文件映射
     *    2.2 应用文件(Application)主要是通过命名规则映射
     */
    protected function initAutoload()
    {
        parent::initAutoload();

        //加载框架Application文件的autoload,匿名函数 -- 开始
        spl_autoload_register(
            function ($className) {

                if (isset($this->classMaps[$className])) {
                    $classFile = $this->classMaps[$className];
                } else {
                    $classFile = str_replace('\\', '/', $className) . '.class.php';
                    $classFile = APP_ROOT.'Application/'.$classFile;
                }

                if (file_exists($classFile)) {
                      include_once $classFile;
                }
            }
        );
        //加载ut测试文件
        spl_autoload_register(
            function ($className) {

                $unitTestFile = str_replace('\\', '/', $className) . '.php';
                $unitTestFile = APP_ROOT.'tests/UnitTest/Application/'.$unitTestFile;

                if (file_exists($unitTestFile)) {
                    include_once $unitTestFile;
                }
            }
        );
        //加载框架Application文件的autoload,匿名函数 -- 开始
    }

    /**
     * 初始化网站运行环境的一些全局变量
     *
     * @author  chloroplast1983
     * @version 1.0.20131016
     */
    protected function initEnv()
    {
        parent::initEnv();
        //加载应用配置文件
        include APP_ROOT.'Application/config.php';
        include APP_ROOT.'Application/widgetRules.php';
    }

    private function initTestEnv()
    {
        $_ENV['APP_ENV'] = 'test';
    }

    protected function getAppPath() : string
    {
        return APP_ROOT;
    }

    /**
     * 初始化错误信息
     */
    protected function initError()
    {
        parent::initError();

        include APP_ROOT.'Application/errorConfig.php'; 
        self::$errorDescriptions = self::$errorDescriptions + include 'Application/errorDescriptionConfig.php';
    }

    /**
     * 初始化数据库
     *
     * @version 1.0.20160204
     */
    protected function initDb()
    {
        parent::initMysql();
        parent::initMongo();
    }

    protected function initCache()
    {
        parent::initMemcached(self::$container->get('memcached.serevice'));
    }
}
