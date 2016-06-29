<?php
namespace tests\UnitTest;

/**
 * 测试框架核心类
 * @author chloroplast
 * @version 1.0.20160218
 */
class CoreTest extends PHPUnit_Framework_TestCase
{

    
    /**
     * 测试自动加载,需要测试如下:
     * 1.测试系统文件自动加载知否正常并且包含全部文件
     * 2.测试Application下的文件是否自动加载正常
     */
    public function testAutoLoad()
    {
        //准备系统文件的文件夹,用于统计系统文件总数 -- 开始
        $_systemFolder = array(S_ROOT.'/System/Command/Cache',
                               S_ROOT.'/System/Classes/',
                               S_ROOT.'/System/Interfaces/',
                               S_ROOT.'/System/Observer/',
                               S_ROOT.'/System/Query/',
                              );
        //准备系统文件的文件夹,用于统计系统文件总数 -- 结束
        //计算文件总数 -- 开始
        $fileCounts = 0;
        foreach ($_systemFolder as $folder) {
            $fileCounts += $this -> getFileCountsFromFolder($folder);
        }
        //计算文件总数 -- 结束
        //测试是否classMaps.php中的sizeof(array)等于文件总数
        $classMaps = include S_ROOT.'System/classMaps.php';
        $this->assertEquals(
            sizeof($classMaps),
            $fileCounts,
            'System file counts: '.$fileCounts.' not equal sizeof classMaps: '.sizeof($classMaps)
        );
        
        //测试classMaps中的class是否自动加载正确
        foreach ($classMaps as $className => $classPath) {
            $this->assertTrue(
                class_exists($className)||interface_exists($className),
                $className.' not autoload by '.$classPath
            );
        }

        //测试Application加载文件,HomeController
        $homeController = new Home\Controller\IndexController();
        $this->assertTrue($homeController instanceof Home\Controller\IndexController, 'Application not autoload');
    }

    /**
     * 获取文件总数,用于测试是否System/classMaps.php中已经包含了全部系统文件
     */
    private function getFileCountsFromFolder($folder)
    {
        $dir = './'.$folder;
        $handle = opendir($folder);
        $i = 0;
        while (false !== $file=(readdir($handle))) {
            if ($file !== '.' && $file != '..') {
                $i++;
            }
        }
        closedir($handle);
        return $i;
    }

    /**
     * 测试是否初始化了Version
     */
    public function testInitVersion()
    {
        $this->assertTrue(defined('MARMOT_VERSION'), 'version not init');
    }

    /**
     * 测试是否初始化了容器
     */
    public function testInitContainer()
    {
        //测试容器已经被初始化了
        $this->assertTrue(is_object(Core::$_container) && Core::$_container instanceof DI\Container);
    }

    /**
     * 测试是否初始化了数据库驱动
     */
    public function testInitDb()
    {
        $this->assertTrue(is_object(Core::$_dbDriver) && Core::$_dbDriver instanceof \System\Classes\MyPdo);
    }

    /**
     * 测试是否初始化了缓存驱动
     */
    public function testInitCache()
    {
        $this->assertTrue(
            is_object(Core::$_cacheDriver) &&
            Core::$_cacheDriver instanceof \Doctrine\Common\Cache\MemcachedCache
        );
    }

    /**
     * 测试是否初始化了环境
     */
    public function testInitEnv()
    {
        global $_FWGLOBAL;
        $this->assertGreaterThan(0, $_FWGLOBAL['timestamp']);//判断是否为大于0的数字
    }
}
