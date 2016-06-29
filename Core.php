<?php
/**
 * core 核心文件
 * @author chloroplast1983
 * @version 1.0.20131007
 */
namespace Core;

//设置底层常量保护包含文件
define('IN_PHP', true);
//默认访问程序路径,请勿修改
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
//通用文件夹目录路径,可以手动修改,需要相对地址
define('G_ROOT', S_ROOT.'Global'.DIRECTORY_SEPARATOR);
//内核部分文件夹路径
define('SYS_ROOT', S_ROOT.'System'.DIRECTORY_SEPARATOR);
//开发环境
define('D_BUG', 1);

/**
 * 文件核心类
 * @author chloroplast1983
 * @version 1.0.20130916
 */
class Core
{
    
    private static $instance;

    //框架内的容器,这里暂时使用的是第三方的PHP-DI容器
    public static $container;

    //缓存驱动
    public static $cacheDriver;

    //数据库驱动
    public static $dbDriver;

    //核心文件映射关系数组
    private $classMaps;
    
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
     * 网站正常启动流程
     */
    public function init()
    {
        //autoload
        self::initAutoload();
        self::initVersion();//初始化网站版本
        self::initContainer();//引入容器
        self::initCache();//初始化缓存使用
        self::initDb();//初始化mysql
        self::initEnv();//初始化环境
        self::initCookie();
        self::initUser();//初始化用户
        self::initInput();
        self::initOutput();
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
        self::initVersion();//初始化网站版本
        self::initEnv();//初始化环境
        self::initContainer();//引入容器
        self::initCache();//初始化缓存使用
        self::initDb();//初始化mysql
    }
    
    /**
     * cli模式专用启动路程,用于引导操作框架的一些操作.在这里我们要实现如下功能:
     * 1. 自动加载
     * 2. 初始化容器
     * 3. 初始化缓存
     * 4. 初始化测试持久层存储
     */
    public function initCli()
    {

        self::initAutoload();//autoload
        self::initEnv();//初始化环境
        self::initContainer();//引入容器
        self::initCache();//初始化缓存使用
        self::initDb();//初始化mysql
    }

    /**
     * 自动加载方法,这里分为2个部分.这里的借鉴了yii框架的自动加载
     *
     * 1. 加载第三方的autoload,主要是composer管理的第三方依赖
     * 2. 核心框架自己的autoload
     *    2.1 核心文件主要是通过映射关系载入的,原来使用的是淘宝的一套开源的autoload,
     *        但是考虑其功能过于繁重,这里改为用文件映射
     *    2.2 应用文件(Application)主要是通过命名规则映射
     */
    private function initAutoload()
    {

        //加载第三方的composer的autoload
        require 'vendor/autoload.php';
        //加载System核心框架内的映射关系 -- 开始
        $this->classMaps = include(SYS_ROOT.'/classMaps.php');
        //加载System核心框架内的映射关系 -- 结束

        //加载框架Application文件的autoload,匿名函数 -- 开始
        spl_autoload_register(function ($className) {

            if (isset($this->classMaps[$className])) {
                $classFile = $this->classMaps[$className];
            } else {
                $classFile = str_replace('\\', '/', $className) . '.class.php';
                $classFile = S_ROOT.'Application/'.$classFile;
            }
            if (file_exists($classFile)) {
                include_once $classFile;
            }
          
        });
        //加载框架Application文件的autoload,匿名函数 -- 开始
    }

    /**
     * 初始化网站运行环境的一些全局变量
     *
     * @global int $_FWGLOBAL['timestamp'] 当前时间的时间戳
     * @author chloroplast1983
     * @version 1.0.20131016
     */
    private function initEnv()
    {
        global $_FWGLOBAL;
        //开启session
        session_start();
        
        $_FWGLOBAL = array();
        
        //设定框架全局时间戳,代替各自调时间函数
        $mtime = explode(' ', microtime());
        $_FWGLOBAL['timestamp'] = $mtime[1];//全局时间戳

        //加载应用配置文件
        require(S_ROOT.'Application/config.php');
    }

    /**
     * 初始化网站版本,主要用于更新每次版本更新后js文件缓存
     * @author chloroplast1983
     * @version 1.0.20131016
     */
    private function initVersion()
    {
        include S_ROOT .'System/pc.version.php';
    }

    /**
     * 创建容器
     * @author chloroplast1983
     * @version 1.0.20160215
     */
    private function initContainer()
    {
        //初始化容器
        $containerBuilder = new DI\ContainerBuilder();
        //这里我们需要使用annotation,所以开启了此功能
        $containerBuilder->useAnnotations(true);
        //为容器设置缓存
        //@todo 开发模式不缓存
        if (!D_BUG) {
            $containerCache = new \Doctrine\Common\Cache\ApcuCache();
            $containerCache->setNamespace('phpcore');
            $containerBuilder->setDefinitionCache($containerCache);
        }
        //为容器设置配置文件
        $containerBuilder->addDefinitions('config.php');
        //创建容器
        self::$container = $containerBuilder->build();
    }

    private function initUser()
    {
        // global $_FWGLOBAL;
        // user::checkauth();
    }
    
    /**
     * 初始化cookie读取
     * @version 1.0.20160204
     */
    private function initCookie()
    {
        // global $_FWCOOKIE;
        // $magic_quote = get_magic_quotes_gpc();
        // //COOKIE
        // $prelength = strlen($_FWC['cookiepre']);
        
        // foreach($_COOKIE as $key => $val) {
        // 	if(substr($key, 0, $prelength) == $_FWC['cookiepre']) {
        // 		$_FWCOOKIE[(substr($key, $prelength))] = empty($magic_quote) ? saddslashes($val) : $val;
        // 	}
        // }
    }
    
    /**
     * 路由,需要解决以前随意由个人设置路由的习惯,
     * 而希望能用统一的路由风格来解决这个问题.
     *
     * @version 1.0.20160204
     */
    private function initInput()
    {
        // global $_FWGLOBAL, $_FWC;

        //创建路由规则,如果对外提供接口考虑token用于验证
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            //添加默认首页路由 -- 开始
            $r->addRoute('GET', '/', ['Home\Controller\IndexController','index']);

            //获取配置好的路由规则
            $routeRules = include(S_ROOT.'/Application/routeRules.php');
            foreach ($routeRules as $route) {
                $r->addRoute($route['method'], $route['rule'], $route['controller']);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                //header:404
                echo '404';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                //header:405
                echo '405';
                break;
            case FastRoute\Dispatcher::FOUND:
                $controller = $routeInfo[1];
                $parameters = $routeInfo[2];
                //安全过滤 -- 开始
                // foreach ($parameters as $key => $value) {
                // 	$parameters[$key] = System\Class\String::htmlFilter($value);
                // }
                //安全过滤 -- 结束
                // ... call $handler with $vars
                self::$container->call($controller, $parameters);
                break;
        }
    }
    
    private function initOutput()
    {
        ob_start();
    }
    
    /**
     * 初始化数据库
     * DBW 标记为数据库写操作,假设操作为单库读写.
     * 如果为一主一从则初始化DBR,并且修改 db/cmodel.class.php 中的 select 函数中的
     * DBW 为 DBR.
     *
     * @todo 暂时还未考虑一主多从的情况,鉴于此情况有如下考虑:
     * 1. 后端使用其他语言开发(golang...),php不在连接数据库
     * 2. 使用第三方的读写分离工具,而不采用程序的读写分离功能
     * 3. 如果还需要使用程序的读写分离,且压力大到需要多读,
     *    则在未来时间内修改框架支持多读实例
     *
     * @version 1.0.20160204
     */
    private function initDb()
    {

        self::$dbDriver = self::$container->get('\System\Classes\MyPdo');
    }

    /**
     * 初始化cache,在框架内暂时把缓存规划为如下几部分:
     * 1. Row cache: 行缓存,针对数据库一行为一段缓存::memcached
     * 2. Vector cache: 关系型缓存,针对关系做缓存处理,
     *    需要考虑不同数据库事务上的支持,
     *    需要额外考虑mysql和redis存储同样的关系怎么保证一致性,
     *    存储为数字对应数字::redis
     * 3. Fragment cache: 片段缓存,用于缓存页面中的一个片段.类似widget的页面内容::memcached
     * 4. Page cache(暂时不考虑处理): 页面缓存,有如下思路:大内容例如产品详情页等,
     *    采用分布式文件系统(淘宝有开源的),存储成静态文件.因为考虑内容
     *    可能超过2MB,超过memcached限制.
     *    主要这部分缓存暂时不想在PHP部分实现,想在后端服务中去实现.
     * @todo
     * 1. 需要优化在使用到memcached的情况下在连接memcached,减少无用的连接.
     *    比如场景为调取一个静态数据,则不需要连接memcached.
     * 2. 这里需要处理如果memcached失效的情况,做的应急处理.
     *
     * @version 1.0.20160204
     */
    private function initCache()
    {
        global $memCacheDriver;

        //初始化memcached缓存 -- 开始
        $memcached = new Memcached();
        $memcached->addServers(self::$container->get('memcached.serevice'));

        self::$cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
        self::$cacheDriver->setMemcached($memcached);
        self::$cacheDriver->setNamespace('phpcore');
        //初始化memcached缓存 -- 结束
    }

    // /**
    //  * 全站钩子调用加载,非私有调用
    //  * @author chloroplast1983
    //  * @version 1.0.20131016
    //  */
    // public function _init_hook(){
    // 	include S_ROOT . 'System/hook/hooks.php';//加载全局钩子文件
    // }
}
