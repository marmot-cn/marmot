#MARMOT框架说明文档

###版本

**v2.0.20160301**

###框架简介

该框架主要是基于`DDD`,`CQRS`的思路去架构.基于`DDD`领域驱动架构,我们可以更好的和用户方来分析项目需求(可以配合使用`四色原型`),大家可以使用通用的领域语言来讨论领域对象.使用`composer`做第三方package管理.`phpunit`做单元测试.开发环境是基于PHP7.0.3做的开发.

主要是解决上个版本框架中的:

1. 自动加载问题
2. memcached 在事务的回滚
3. REST形式的路由
4. phpunit的引入
5. 引入composer来管理第三方包
6. 引入DDD思想来进行解耦
7. CQRS的分离
8. 配合四色原型来进行业务分析.

更多是想把一些基于数据库编程(一个表单为一个对象)的思路,解耦为领域对象和存储层.数据库的表只是作为一个存储层来考虑.领域对象是我们通用讨论的对象.

###开发环境下载

docker-compose.yml(待补全)

###开始

1. 下载代码
2. 下载并运行镜像

		docker-compose up -d
		
3. 下载第三方扩展

		composer install

4. 可以开始使用,默认路由会指向`Home/Controller/IndexController.class.php`中的`index()`

		输出 Hello World

###框架目录

####Application

应用目录,我们构建的代码都会放在该目录.

		Application
		--User(AppName)
			--Command
			--Controller
			--Model
			--Persistence
			--Repository
				--Query
		config.php
		routeRules.php
			
**AppName**

这里我们用`User`为示例代码.这里放我们各个`领域`的代码.里面我们使用命名空间来划分`领域的上下文`.

**Command**

存放我们`C`qrs中的`Command`.一般我们的命名规则为: `动词`+`AppName`+`Command`

		注册用户命令
		SignUpUserCommand.class.php
		
如果一个模块内的领域对象较多,可以在该目录下在新建一个对应领域对象名字的文件夹.一套领域对象的命令集合统一创建一个`工厂文件`来封装命令的调用.

**Controller**

应用层服务,主要用于协调调用`领域服务`和`仓库`文件

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

**config.php**

主要存放我们项目使用的常量.

		define('xxx',xxx);

**routeRules.php**

路由文件,设定路由规则匹配我们的controller文件.

####System

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
		--Pcommand.class.php
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
		classMaps.php
		pc.version.php

**Classes**


####tests

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

数据库基境,具体细节可以参见PHPunit中的数据库测试章节.主要存放我们到处的数据库数据xml文件.在使用前需要先把数据库的表建立.

导出xml文件方法:

		mysqldump --xml -t -u xxx --password=xxxx databasename tablename > /path/xx.xml

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


###命名规范

1. 变量命名为`驼峰`.
2. 类的命名规则为第`一`个字母`大写`.
3. Command命名规范: `动词`+`名称`+`Command`
4. Repository命名规范: `名称`+`Repository`
5. Persistence命名规范: `名称`+`Db|Cache`
6. Query命名规范: `名称`+`FragmentCacheQuery|RowCacheQuery|RowQuery|SearchQuery|VectorQuery`

###注释规范

参考[phpdoc](https://www.phpdoc.org// "phpdoc")


		
		
