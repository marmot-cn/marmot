# MARMOT 后端接口框架说明文档

[![Build Status](https://www.travis-ci.org/chloroplast1983/marmot.svg?branch=master)](https://www.travis-ci.org/chloroplast1983/marmot)

## 版本

* 2.0
	* [2.1.0](./Docs/Version/2.1.md "2.1")
	* [2.2.0](./Docs/Version/2.2.md "2.2")
	* [2.3.0](./Docs/Version/2.3.md "2.3")`开发中`

## 简介

具体说明可见[wiki](https://github.com/chloroplast1983/marmot/wiki)

## quick start

创建代码目录映射属主和属组

```
sudo groupadd -g 33 www-data
sudo useradd www-data -u 1020 -g www-data
```

创建`mysql`数据库目录映射属主和属组

```
sudo groupadd -g 1020 mysql
sudo useradd mysql -u 1020 -g mysql
```

创建`mongo`数据库目录映射属主和属组

```
sudo groupadd -g 1010 mongo
sudo useradd mysql -u 1010 -g mongo
```

克隆代码

```
git clone https://github.com/chloroplast1983/marmot
```

启动环境

```
docker-compose up -d
```

更新扩展包

```
docker exec -it marmot-phpfpm composer install
```

