# MARMOT微服务框架说明文档

### 版本

* 2.0
	* [2.1.0](./Docs/Version/2.1.md "2.1")
	* [2.2.0](./Docs/Version/2.2.md "2.2")
	* [2.3.0](./Docs/Version/2.3.md "2.3")`开发中`

### 目录

* [框架简介](#abstract) 
* [环境搭建](#environment)
* [composer](#composer)
* [Hello World](#helloworld)
* [框架目录](#framework)
* [marmot](#marmot)
* [自动化](#automatic)
* [规范](#rule)
* [更新记录](./changelog.md "change log")

### [框架简介](id:abstract)


该框架主要是基于`DDD`,`CQRS`的思路去架构.基于`DDD`领域驱动的`微服务`架构,我们可以更好的和用户方来分析项目需求(可以配合使用`四色原型`),大家可以使用通用的领域语言来讨论领域对象.使用`composer`做第三方`package`管理.`phpunit`做单元测试.开发环境是基于PHP7.0.3做的开发.

主要是解决上个版本框架中的:

1. 自动加载问题
2. memcached 在事务的回滚
3. REST形式的路由
4. phpunit的引入
5. 引入composer来管理第三方包
6. 引入DDD思想来进行解耦
7. CQRS的分离
8. 配合四色原型来进行业务分析
9. 自动化工具
	* 自动化测试
	* 自动化代码风格检测
	* 自动化代码覆盖检测
	* .... 

更多是想把一些基于数据库编程(一个表单为一个对象)的思路,解耦为领域对象和存储层.数据库的表只是作为一个存储层来考虑.领域对象是我们通用讨论的对象.

采取事件驱动模式开发:

`Command` -> `CommandBus` -> `CommandHandler` -> `Event`

### [环境搭建](id:environment)

**下载docker**

[https://docs.docker.com](https://docs.docker.com ,"https://docs.docker.com")

根据自己的环境版本选择docker环境.

还没使用过`docker for windows`,可以考虑使用虚拟机来进行开发.

**下载docker-compose**

		curl -L https://github.com/docker/compose/releases/download/1.8.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
		
		$ chmod +x /usr/local/bin/docker-compose
		
		$ docker-compose --version
		docker-compose version: 1.8.0

**使用docker-compose安装开发环境**

这里使用的PHP 7.0.3 镜像,已经编译进`mongo`,`redis`,`memcached`,`pthreads`等一些常用的扩展.

端口:

* `80`: 程序访问端口号
* `10081`: phpmyadmin访问端口号

在根目录可以看见`docker-compose`文件,运行:

		docker-compose up -d 
		
启动我们的开发环境,如果没有镜像会自动拉去的

### [composer](id:composer)

#### 命令

**生产环境**

不需要安装开发包:

		composer install --no-dev
		
**开发环境**

默认安装所有包

		composer install

#### dev 开发中使用

##### phploc/phploc

衡量我们项目大小的工具

[https://github.com/sebastianbergmann/phploc](https://github.com/sebastianbergmann/phploc "https://github.com/sebastianbergmann/phploc")

		vendor/bin/phploc --progress Application System
		phploc 3.0.1 by Sebastian Bergmann.

		Directories                                          8
		Files                                               34
		
		Size
		...

##### phpmd/phpmd

wait...

[https://phpmd.org](https://phpmd.org "https://phpmd.org")

##### sebastian/phpcpd

Copy/Paste Detector

[https://github.com/sebastianbergmann/phpcpd/](https://github.com/sebastianbergmann/phpcpd/ "https://github.com/sebastianbergmann/phpcpd/")
	
我们扫描`Application`,`System`和`tests`文件夹:

		vendor/bin/phpcpd Application System tests
		phpcpd 2.0.4 by Sebastian Bergmann.

		0.00% duplicated lines out of 4319 total lines of code.
		
		Time: 935 ms, Memory: 6.00MB

[https://pdepend.org](https://pdepend.org "https://pdepend.org")

##### phpmd/phpmd

[https://phpmd.org/](https://phpmd.org "https://phpmd.org")

		vendor/bin/phpmd ./Application text ruleset.xml

		
##### phpunit/phpunit phpunit/dbunit

单元测试

配置文件`phpunit.xml`

[http://phpunit.de/](http://phpunit.de/ "http://phpunit.de/")


		vendor/bin/phpunit
		PHPUnit 5.2.6 by Sebastian Bergmann and contributors.
		
		................................................................. 65 / 86 ( 75%)
		.....................                                             86 / 86 (100%)
		
		Time: 8.4 seconds, Memory: 6.00MB
		
		OK (86 tests, 262 assertions)

`生成代码覆盖报告`:

		phpdbg -qrr ./vendor/bin/phpunit --coverage-html ./Docs/xxx

##### squizlabs/php_codesniffer

代码风格检测

配置文件`phpcs.xml`

[https://github.com/squizlabs/PHP_CodeSniffer/](https://github.com/squizlabs/PHP_CodeSniffer/ "https://github.com/squizlabs/PHP_CodeSniffer/")


		vendor/bin/phpcs
		....................................................
		
		Time: 3.97 secs; Memory: 8Mb
		
##### fzaninotto/faker

数据生成器,主要用于生成我们在测试文件的数据.以及生成我们数据库的`xml`文件.

[https://github.com/fzaninotto/Faker](https://github.com/fzaninotto/Faker "https://github.com/fzaninotto/Faker")

使用方法:
		
		$faker = \Faker\Factory::create('zh_CN');//我们使用中国的表述信息
		$faker->seed(种子数字);//'种子数字'相同则生成数据一致
		
		$faker->name;//中文名
		$faker->phoneNumber;//手机号
		
`常用fake数据`:

* `随机单个数不为空`: `$faker->randomDigitNotNull`
* `随机单个数可为空`: `$faker->randomDigit`
* `随机数`: `$faker->randomNumber($nbDigits = NULL) // 79907610`
* `随机小树`: `$faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL) // 48.8932`
* `随机范围数`: `$faker->numberBetween($min = 1000, $max = 9000) // 8567`
* `随机字母`: `$faker->randomLetter`
* `随机元素`: `$faker->randomElement($array = array ('a','b','c')) // 'b'`
* `手机号`: `$faker->phoneNumber`
* `用户名`: `$faker->userName`
* `昵称`: `$faker->userName`
* `密码`: `md5($faker->password)`
* `类型,状态`: `$faker -> randomElement($array = array (xxx, xx))`
* `时间`: `$faker->unixTime()`
* `银行卡号`: `$faker->creditCardNumber`

#### 通用

##### mongodb/mongodb

[github](https://github.com/mongodb/mongo-php-library "https://github.com/mongodb/mongo-php-library")

[mongo-php 文档链接](http://mongodb.github.io/mongo-php-library/ "http://mongodb.github.io/mongo-php-library/")

[mongo官方文档](https://docs.mongodb.com/ "https://docs.mongodb.com/")

php mongoDb 封装使用包,封装了我们对mongo的常用操作.

我们使用mongo主要用于存储一些非格式化数据.

**插入单条数据**

		$collection = Core::$mongoDriver->库->Collection;
		$result = $collection->insertOne( array(xxx) );
		$result->getInsertedId();//返回上次插入id
		
**获取单条数据**
		
		$collection = Core::$mongoDriver->库->Collection
		$document = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectID('xxxx')]);
		var_dump((array)$document);

**获取多条数据**

		$collection = Core::$mongoDriver->库->Collection
		$cursor = $collection->find(['_id' => ['$in' => [new \MongoDB\BSON\ObjectID('xx'),new \MongoDB\BSON\ObjectID('xx'),new \MongoDB\BSON\ObjectID('xx')]]]);
		
		foreach ($cursor as $document) {
			echo $document['_id'], "\n";
		}



### [HelloWorld](id:helloworld)

1. 下载代码
2. 下载并运行镜像

		docker-compose up -d
		
3. 下载第三方扩展

		composer install

4. 可以开始使用,默认路由会指向`Home/Controller/IndexController.class.php`中的`index()`

		输出 Hello World

### [框架目录](id:framework)

#### Application

应用目录,我们构建的代码都会放在该目录.

		Application
		--Common(通用)
			--Controller
				--JsonApiController.class.php
			--View
				--JsonApiView.class.php
		--User(AppName)
			--Command
			--CommandHandler
			--Controller
			--Model
			--Persistence
			--Repository
				--Query
			--Services
		config.php
		routeRules.php
			
**AppName**

这里我们用`User`为示例代码.这里放我们各个`领域`的代码.里面我们使用命名空间来划分`领域的上下文`.

**Command**

在`应用服务层`通过`命令`来传输数据.

**CommandHandler**

`命令控制器`来处理`命令`.

交互模式为:

`Command` -> `CommandBus` -> `CommandHandler` -> `Event`

**Controller**

应用层服务,主要用于协调调用`命令(写)`和`仓库(读)`文件

**Model**

领域对象,内部封装自己部分业务逻辑.

**Persistence**

持久层,一般我们会存放2类文件:

1. xxDb.class.php: 数据层文件
2. xxCache.class.php: 缓存层文件

**Repository**

仓库,每个领域对象一般会对应拥有一个仓库文件.

**Query**

我们存放查询层的封装,一般我们会存放如下几类文件:

1. xxFragmentCacheQuery: 片段缓存文件
2. xxRowCacheQuery: 行缓存文件
3. xxRowQuery: 行查询文件
4. xxSearchQuery: 搜索查询文件
5. xxVectorQuery: 关系型缓存文件

**Services**

领域服务层,用于协调多个领域对象的操作

**config.php**

主要存放我们项目使用的常量.

		define('xxx',xxx);

**routeRules.php**

路由文件,设定路由规则匹配我们的controller文件.

常用路由:

* `/xxx[/{ids:[\d,]+}]`
	* xxx.com/xxx/id
	* xxx.com/xxx/id1,id2,id3
	* xxx.com/xxx?filter[]...
* `/xxx/{id:\d+}`
	* xxx/com/xxx/id 

#### System

框架核心文件存放位置,文件路径为:

		Classes
		--Cache.class.php
		--Db.class.php
		--MyPdo.class.php
		--Transaction.class.php
		Command
		--Cache
			--DelCacheCommand.class.php
			--SaveCacheCommand.class.php
		Interfaces
		--CacheLayer.class.php
		--Command.class.php
		--DbLayer.class.php
		--Observer.class.php
		--ICommand.class.php
		--ICommandHandler.class.php
		--ICommandHandlerFactory.class.php
		--ResponseFormatterInterface.class.php
		--Subject.class.php
		--Widge.class.php
		Observer
		--CacheObserver.class.php
		--Subject.class.php
		Query
		--FragmentCacheQuery.class.php
		--RowCacheQuery.class.php
		--RowQuery.class.php
		--SearchQuery.class.php
		--VectorQuery.class.php
		View
		--EmptyView.class.php
		--JsonApiResponseFormatter.class.php
		classMaps.php
		pc.version.php

**Classes**


#### tests

测试文件夹,文件路径为

		Fixtures
		IntegrationTest
		UnitTest
		--Application
		--System
		CoreTest.php
		GenericTestCase.php
		GenericTestDataBaseTestCase.php
		
**Fxitures**

每张表的数据对应一张表.在表内通过使用`Faker`自动生成数据.

**IntegrationTest**

集成测试用例存放位置

**UnitTest**

单元测试存放位置,其中`Application`文件夹存放我们应用服务的单元测试文件.`System`存放系统测试的单元测试文件.

**CoreTest.php**

测试`Core.php`的单元测试文件

**GenericTestCase.php**

框架封装的常规测试文件,继承该类即可使用PHPunit通用方法.额外提供:

1. `getPrivateMethod`: 测试私有方法
2. `getPrivateProperty`: 测试私有属性

**GenericTestsDatabaseTestCase.php**

框架封装的数据测试文件,继承该类即可方便的使用数据库测试方法,且也会额外的封装`GenericTestCase.php`中测试私有方法和私有属性函数.

**MyAppDbUnitArrayDataSet.php**

生成测试数据库文件,我们这里主要通过`Faker`生成测试数据到数据表内.过去我们需要手动添加测试数据较为麻烦.并且数据较为不规范,我们现在选用`php`文件生成.

### [自动化](id:automatic)

我们使用`git`的`提交钩子`来触发自动化提交.

使用:

		 cp pre-commit .git/hooks/
		 
**检查目标**

* 代码格式是否为`PSR2`
* 代码复制黏贴检测
* 单元测试

#### deployment 部署使用的脚本

**`deployment/sandbox`**

沙箱环境部署脚本文件,自动化部署时读取该文件

**`deployment/production`**

生产环境部署脚本文件,自动化部署时候读取该文件

**`deployment/test`**

测试环境部署脚本文件,做`CI`集时候部署的脚本文件

#### database 持续集成的数据库脚本

**`database/database.sql`**

测试数据库加载表结构文件

**`database/test.sql`**

测试数据库加载系统测试表结构文件

#### Jenkinsfile

jenkins 系统使用的部署脚本文件

#### VERSION

我们每次开发前制定的版本号,如果测试通过会自动读取该文件的版本号并推送到版本仓库内

#### conf 配置文件模板

基于`配置隔离`思想,我们把代码和我们的配置信息分隔开.使用的工具是`confd`

**`conf/production`**

生产环境模板文件

**`conf/sandbox`**

沙箱配置模板文件

#### Dockerfile.dev

制作数据卷容器的脚本文件

#### sync-config.sh

同步配置文件脚本

### [marmot](id:marmot)

脚手架工具.

		root@0967b4c11e7e:/var/www/html# marmot
		Usage: php marmot.php COMMAND [arg...]
		       php marmot.php [help | -h | -v | version]
		Commands:
			autoCreate	Add new model file and unitTest file automaticly
			cacheClear	Clear cache in marmot framework

**清理缓存**

		php marmot.php cacheClear
		
		root@0967b4c11e7e:/var/www/html# php marmot.php cacheClear
		memcached                                                         [  ok  ]
		apcu                                                              [  ok  ]

**生成模型文件**

		

### [规范](id:rule)

#### 命名规范

1. 变量命名为`驼峰`.
2. 类的命名规则为第`一`个字母`大写`.
3. Command命名规范: `动词`+`名称`+`Command`
4. Repository命名规范: `名称`+`Repository`
5. Persistence命名规范: `名称`+`Db|Cache`
6. Query命名规范: `名称`+`FragmentCacheQuery|RowCacheQuery|RowQuery|SearchQuery|VectorQuery`

#### 注释规范

参考[phpdoc](https://www.phpdoc.org// "phpdoc")

#### 接口版本

`MAJOR.MINOR.PATCH`

* `MAJOR`: 改变意味着其中包含向后不兼容的修改.
* `MINOR`: 改变意味这有新功能的增加,但应该是向后兼容的.
* `PATCH`: 改变代表对已有功能的缺陷修复.