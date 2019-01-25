# 用户接口示例

---

* [获取单条数据](#获取单条数据)
* [获取多条数据](#获取多条数据)
* [根据检索条件查询数据](#根据检索条件查询数据)
* [注册](#注册)
* [登录](#登录)
* [接口返回示例](#接口返回示例)

### 获取数据支持include、fields请求参数

	1、include请求参数
	2、fields[TYPE]请求参数
	    2.1 fields[users]
	3、page请求参数
		3.1 page[number]=1 | 当前页
		3.2 page[size]=20 | 获取每页的数量

示例

	$response = $client->request('GET', 'users?fields[users]=cellphone,userName&page[number]=1&page[size]=10', ['headers'=>['Accept' => 'application/vnd.api+json']]);

### <a name="获取单条数据">获取单条数据示例</a>

路由

	通过GET传参
	/users/{id:\d+}

示例

	$response = $client->request('GET', 'users/1', ['headers'=>['Accept' => 'application/vnd.api+json']]);

### <a name="获取多条数据">获取多条数据示例</a>

路由

	通过GET传参
	/users/{ids:\d+,[\d,]+}

示例

	$response = $client->request('GET', 'users/1,2,3', ['headers'=>['Accept' => 'application/vnd.api+json']]);

### <a name="根据检索条件查询数据">根据检索条件查询数据示例</a>

路由

	通过GET传参
	/users

	1、检索条件
	    1.1 filter[cellphone] | 根据手机号搜索
	    1.2 filter[status] | 根据状态搜索 | 0 正常 | -2 删除
	2、排序
		2.1 sort=-id | -id 根据id倒序 | id 根据id正序
		2.2 sort=-updateTime | -updateTime 根据更新时间倒序 | updateTime 根据更新时间正序

示例

	$response = $client->request('GET', 'users?sort=-id', ['headers'=>['Accept' => 'application/vnd.api+json']]);

### <a name="注册">注册示例</a>

路由

	通过POST传参
	/users

示例

	$data = array("data"=>array(
		            "type"=>"users",
	                    "attributes"=>array(
				"cellphone"=>"18800000000",   
				"password"=>"Admin1241"
			       )
	                   )
	        );
	$response = $client->request(
	                'POST',
	                'users',
	                [
	                    'headers'=>[
	                        'Accept' => 'application/vnd.api+json', 
	                        'Content-Type' => 'application/vnd.api+json'
	                    ],
	                    'json' => $data
	                ]
	            );

### <a name="登录">登录示例</a>

路由

	通过POST传参
	/users/signIn

示例

	$data = array("data"=>array(
			    "type"=>"users",
	                    "attributes"=>array(
				"cellphone"=>"18800000000",   
				"password"=>"Admin1241"
			       )
	                   )
	        );
	$response = $client->request(
	                'POST',
	                'users/signIn',
	                [
	                    'headers'=>[
	                        'Accept' => 'application/vnd.api+json', 
	                        'Content-Type' => 'application/vnd.api+json'
	                    ],
	                    'json' => $data
	                ]
	            );

### <a name="接口返回示例">接口返回示例</a>

	{
	    "meta": [],
	    "data": {
	        "type": "users",
	        "id": "1",
	        "attributes": {
	            "cellphone": "18800000000",
	            "userName": "18800000000",
	            "realName": "",
	            "nickName": "",
	            "status": 0,
	            "createTime": 1543828524,
	            "updateTime": 0,
	            "statusTime": 0
	        },
	        "links": {
	            "self": "127.0.0.1:8080\/users\/1"
	        }
	    }
	}
