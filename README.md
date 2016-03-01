#MARMOT框架说明文档

###版本

**v1.0.20160301**

###框架简介

该框架主要是基于`DDD`,`CQRS`的思路去架构.使用`composer`做第三方package管理.`phpunit`做单元测试.

开发环境是基于PHP7.0.3做的开发.

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





		
		
