#changelog

---

**v1.0**

是基于ORM模式开发的框架,应用在yyets,以及公司的项目上.

**v2.0.0**

`20160829`

release版本.

**v2.0.1**

`20170106`

* 修改数据库插入bug,当插入失败时直接返回false,不在判断`last_insert_id`.
* 默认环境 nginx-phpfpm 升级为 `1.1`.
* `Application/Common/Controller/JsonApiController.class.php` 添加了 `getSort()` 方法.
* `RowQuery.class.php`,添加 `add()` 和 `update()` 方法.