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
use Marmot\Framework\Application\IApplication;

use Marmot\Application\Application;

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
        $this->initAutoload();//autoload
        $this->initTestEnv();//初始化测试环境
        $this->initContainer();//引入容器
        $this->initEnv();//初始化环境
        $this->initCache();//初始化缓存使用
        $this->initDb();//初始化mysql
        $this->initMongo();
        $this->initError();
    }
    
   protected function initApplication() : void
    {
        $this->application = new Application();
    }

    protected function getApplication() : IApplication
    {
        return $this->application;
    }

    private function initTestEnv()
    {
        $_ENV['APP_ENV'] = 'test';
    }

    protected function getAppPath() : string
    {
        return APP_ROOT;
    }

    protected function initAutoload()
    {
        
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
