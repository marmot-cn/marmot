<?php
/**
 * User/Model/User.class.php 测试文件,测试所有set正确
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserTest extends GenericTestCase{

	private $stub;

	public function setUp(){
		$this->stub = new User\Model\User();
	}

	/**
	 * 测试构造函数的初始化情况
	 */
	public function testUserConstructor(){
		//测试初始化id
		$idParameter = $this->getPrivateProperty('User\Model\User','id');
		$this->assertEquals(0,$idParameter->getValue($this->stub));

		//测试初始化 cellPhone
		$cellPhoneParameter = $this->getPrivateProperty('User\Model\User','cellPhone');
		$this->assertEmpty($cellPhoneParameter->getValue($this->stub));
	
		//测试初始化 password
		$passwordParameter = $this->getPrivateProperty('User\Model\User','password');
		$this->assertEmpty($passwordParameter->getValue($this->stub));
	
		//测试初始化 salt
		$saltParameter = $this->getPrivateProperty('User\Model\User','salt');
		$this->assertEmpty($saltParameter->getValue($this->stub));
	
		//测试初始化 avatar
		$avatarParameter = $this->getPrivateProperty('User\Model\User','avatar');
		$this->assertEquals(0,$avatarParameter->getValue($this->stub));

		//测试初始化 realName
		$realNameParameter = $this->getPrivateProperty('User\Model\User','realName');
		$this->assertEmpty($realNameParameter->getValue($this->stub));

		//测试初始化 Area $province
		$provinceParameter = $this->getPrivateProperty('User\Model\User','province');
		$this->assertInstanceof('Area\Model\Area',$provinceParameter->getValue($this->stub));

		//测试初始化 Area $city
		$cityParameter = $this->getPrivateProperty('User\Model\User','city');
		$this->assertInstanceof('Area\Model\Area',$cityParameter->getValue($this->stub));

		//测试初始化 Area $district
		$districtParameter = $this->getPrivateProperty('User\Model\User','district');
		$this->assertInstanceof('Area\Model\Area',$districtParameter->getValue($this->stub));

		//测试初始化 colleage
		$colleageParameter = $this->getPrivateProperty('User\Model\User','colleage');
		$this->assertEmpty($colleageParameter->getValue($this->stub));

		//测试初始化 birthday
		$birthdayParameter = $this->getPrivateProperty('User\Model\User','birthday');
		$this->assertEmpty($birthdayParameter->getValue($this->stub));

		//测试初始化 gender
		$genderParameter = $this->getPrivateProperty('User\Model\User','gender');
		$this->assertEquals(0,$genderParameter->getValue($this->stub));		
	
		//测试初始化 subject
		$subjectParameter = $this->getPrivateProperty('User\Model\User','subject');
		$this->assertEquals(0,$subjectParameter->getValue($this->stub));

		//测试初始化 email
		$emailParameter = $this->getPrivateProperty('User\Model\User','email');
		$this->assertEmpty($emailParameter->getValue($this->stub));

		//测试初始化 qq
		$qqParameter = $this->getPrivateProperty('User\Model\User','qq');
		$this->assertEmpty($qqParameter->getValue($this->stub));

		//测试初始化createTime时间戳
		$crateTimeParameter = $this->getPrivateProperty('User\Model\User','createTime');
		$this->assertGreaterThan(0,$crateTimeParameter->getValue($this->stub));

		//测试初始化status
		$statusParameter = $this->getPrivateProperty('User\Model\User','status');
		$this->assertEquals(0,$statusParameter->getValue($this->stub));	

		//测试初始化 lastScore
		$lastScoreParameter = $this->getPrivateProperty('User\Model\User','lastScore');
		$this->assertEquals(0,$lastScoreParameter->getValue($this->stub));

		//测试初始化 IsVip
		$isVipParameter = $this->getPrivateProperty('User\Model\User','isVip');
		$this->assertFalse($isVipParameter->getValue($this->stub));
	}

	//id
	/**
	 * 设置User setId() 正确的传参类型,期望传值正确
	 */
	public function testUserSetIdCorrectType(){
		//赋值int
		$this->stub->setId(1);
		//期望得到正确的返回传参
		$this->assertEquals(1,$this->stub->getId());
	}

	/**
	 * 设置User setId() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetIdWrongType(){
		//复制string
		$this->stub->setId('id');
	}

	/**
	 * 设置User setId() 错误的传参类型,但是传参是数值
	 */
	public function testUserSetIdWrongTypeButNumeric(){
		$this->stub->setId('1');
		$this->assertTrue(is_int($this->stub->getId()));
		$this->assertEquals(1,$this->stub->getId());
	}

	//CellPhone
	/**
	 * 设置User setCellPhone() 正确的传参类型,期望返回正确的值
	 */
	public function testUserSetCellPhoneCorrectType(){
		//赋值
		$this->stub->setCellPhone('15202939435');
		//期望得到正确的返回传参
		$this->assertEquals('15202939435',$this->stub->getCellPhone());
	}

	/**
	 * 设置User setId() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetCellPhoneWrongType(){
		//string
		$this->stub->setCellPhone(array(13422342324,13422342324));
	}

	/**
	 * 设置User setCellPhone() 正确的传参类型,期望返回空
	 */
	public function testUserSetCellPhoneCorectTypeNotNumber(){
		//赋值
		$this->stub->setCellPhone('1520293943a');
		//期望得到正确的返回传参
		$this->assertEquals('',$this->stub->getCellPhone());
	}

	//password
	/**
	 * 设置User setPassword() salt传空,期望产生salt值和加密过的密码
	 */
	public function testUserSetPasswordWithoutSalt(){
		//初始化密码
		$password = '111111';
		$this->stub->setPassword($password);

		//确认密码是一个32位长度和salt一起加密过的md5值
		$this->assertEquals(32,strlen($this->stub->getPassword()));

		//确认盐是一个4位长度
		$this->assertEquals(4,strlen($this->stub->getSalt()));
	}

	/**
	 * 设置User setPassword() 
	 * 
	 * 1. 先生成密码和salt
	 * 2. 传入salt和原始密码,确认再次加密后的值和第一次生成的密码一致
	 */
	public function testUserSetPasswordWithSalt(){
		//初始化密码
		$password = '111111';
		$this->stub->setPassword($password);	
		$salt = $this->stub->getSalt();

		//初始化一个新的用户,再次加密
		$anotherUser = new User\Model\User();
		$anotherUser->setPassword($password,$salt);

		//校验第一次生成的密码和盐,再次加密期望一致
		$this->assertEquals($this->stub->getPassword(),$anotherUser->getPassword());
	}

	//Province
	/**
	 * 设置 User setProvince() 正确的传参类型,期望返回正确的对象
	 */
	public function testUserSetProvinceCorrectType(){
		//初始化
		$area = new Area\Model\Area();
		$area -> setId(5);
		$area -> setName('areaName');
		$area -> setParentId(1);

		//赋值
		$this->stub->setProvince($area);

		//判断返回对象值正确
		$this->assertSame($area,$this->stub->getProvince());
	}

	/**
	 * 设置 User setProvince() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetProvinceWrongType(){
		//string
		$this->stub->setProvince('陕西');
	}

	//City
	/**
	 * 设置 User setCity() 正确的传参类型,期望返回正确的对象
	 */
	public function testUserSetCityCorrectType(){
		//初始化
		$area = new Area\Model\Area();
		$area -> setId(311);
		$area -> setName('areaName');
		$area -> setParentId(24);

		//赋值
		$this->stub->setCity($area);

		//判断返回对象值正确
		$this->assertSame($area,$this->stub->getCity());
	}

	/**
	 * 设置 User setCity() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetCityWrongType(){
		//string
		$this->stub->setCity('西安');
	}

	//District
	/**
	 * 设置 User setDistrict() 正确的传参类型,期望返回正确的对象
	 */
	public function testUserSetDistrictCorrectType(){
		//初始化
		$area = new Area\Model\Area();
		$area -> setId(311);
		$area -> setName('areaName');
		$area -> setParentId(24);

		//赋值
		$this->stub->setDistrict($area);

		//判断返回对象值正确
		$this->assertSame($area,$this->stub->getDistrict());
	}

	/**
	 * 设置 User setDistrict() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetDistrictWrongType(){
		//string
		$this->stub->setDistrict('西安');
	}

	//Avatar
	/**
	 * 设置User setAvatar() 正确的传参类型,期望传值正确
	 */
	public function testUserSetAvatarCorrectType(){
		//赋值int
		$this->stub->setAvatar(1);
		//期望得到正确的返回传参
		$this->assertEquals(1,$this->stub->getAvatar());
	}

	/**
	 * 设置User setAvatar() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetAvatarWrongType(){
		//string
		$this->stub->setAvatar('avatar');
	}

	/**
	 * 设置User setAvatar() 错误的传参类型,但是传参是数值
	 */
	public function testUserSetAvatarWrongTypeButNumeric(){
		$this->stub->setAvatar('10');
		$this->assertTrue(is_int($this->stub->getAvatar()));
		$this->assertEquals(10,$this->stub->getAvatar());
	}

	//RealName
	/**
	 * 设置 User setRealName() 正确的传参类型,期望传值正确
	 */
	public function testUserSetRealNameCorrectType(){
			//赋值string
			$this->stub->setRealName("petter");
			//期望得到正确的返回传参
			$this->assertEquals("petter",$this->stub->getRealName());
		}
	/**
	 * 设置 User setRealName() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetRealNameWrongType(){
		//array
		$this->stub->setRealName(array('haha','tommy'));
	}

	//Colleage
	/**
	 * 设置 User setColleage() 正确的传参类型,期望传值正确
	 */
	public function testUserSetColleageCorrectType(){
			//赋值string
			$this->stub->setColleage("高新一中");
			//期望得到正确的返回传参
			$this->assertEquals("高新一中",$this->stub->getColleage());
		}
	/**
	 * 设置 User setColleage() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetColleageWrongType(){
		//array
		$this->stub->setColleage(array('高新一中','铁一中'));
	}

	//Birthday
	/**
	 * 设置 User setBirthday() 正确的传参类型,期望传值正确
	 */
	public function testUserSetBirthdayCorrectType(){
			//赋值string
			$this->stub->setBirthday("1990-01-01");
			//期望得到正确的返回传参
			$this->assertEquals("1990-01-01",$this->stub->getBirthday());
		}
	/**
	 * 设置 User setBirthday() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetBirthdayWrongType(){
		//array
		$this->stub->setBirthday(array('1990-01-01','1990-01-02'));
	}
	/**
	 * 设置User setBirthday() 正确的传参类型,期望返回空
	 */
	public function testUserSetBirthdayCorectTypeNotNumber(){
		//赋值
		$this->stub->setBirthday('1990-a-b');
		//期望得到正确的返回传参
		$this->assertEquals('',$this->stub->getBirthday());
	}

	//Gender
	/**
	 * 设置User setGender() 
	 * 
	 * @dataProvider UserGenderProvider
	 */
	public function testUserSetGender($gender,$expected){
    	$this->stub->setGender($gender);
        $this->assertEquals($expected,$this->stub->getGender());
    }

    /**
     * user Status 数据构造器
     */
	public function UserGenderProvider(){
        return array(
          array(USER_GENDER_MAN, USER_GENDER_MAN),
          array(USER_GENDER_WOMAN, USER_GENDER_WOMAN),
          array(USER_GENDER_NOT_SET, USER_GENDER_NOT_SET),
          array(999, USER_GENDER_NOT_SET)
        );
    }

	/**
	 * 设置User setGender() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetGenderWrongType(){
		//string
		$this->stub->setGender('gender');
	}

	//Subject
	/**
	 * 设置User setSubject() 
	 * 
	 * @dataProvider UserSubjectProvider
	 */
	public function testUserSetSubject($subject,$expected){
    	$this->stub->setSubject($subject);
        $this->assertEquals($this->stub->getSubject(),$expected);
    }

    /**
     * user Status 数据构造器
     */
	public function UserSubjectProvider(){
        return array(
          array(SUBJECT_ARTS, SUBJECT_ARTS),
          array(SUBJECT_SCIENCE, SUBJECT_SCIENCE),
          array(999, SUBJECT_ARTS)
        );
    }

	/**
	 * 设置User setSubject() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetSubjectWrongType(){
		//string
		$this->stub->setSubject('id');
	}

	/**
	 * 设置User setSubject() 正确的传参类型,超出传参范围,期望传值正确
	 */
	public function testUserSetSubjectCorrectTypeOutOfRange(){
		//赋值int
		$this->stub->setSubject(SUBJECT_ARTS+10);
		//期望得到正确的返回传参
		$this->assertEquals(SUBJECT_ARTS,$this->stub->getSubject());
	}

	//Email
	/**
	 * 设置 User setEmail() 正确的传参类型,期望传值正确
	 */
	public function testUserSetEmailCorrectType(){
		//赋值string
		$this->stub->setEmail("12@qq.com");
		//期望得到正确的返回传参
		$this->assertEquals("12@qq.com",$this->stub->getEmail());
	}
	/**
	 * 设置 User setEmail() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetEmailWrongType(){
		//array
		$this->stub->setEmail(array('12@qq.com','123@qq.com'));
	}
	/**
	 * 设置User setEmail() 正确的传参类型,期望返回空
	 */
	public function testUserSetEmailCorectTypeNotNumber(){
		//赋值
		$this->stub->setEmail('213123213sdasdas');
		//期望得到正确的返回传参
		$this->assertEquals('',$this->stub->getEmail());
	}

	//Qq
	/**
	 * 设置 User setQq() 正确的传参类型,期望传值正确
	 */
	public function testUserSetQqCorrectType(){
			//赋值string
			$this->stub->setQq("1231231232");
			//期望得到正确的返回传参
			$this->assertEquals("1231231232",$this->stub->getQq());
		}
	/**
	 * 设置 User setQq() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetQqWrongType(){
		//array
		$this->stub->setQq(array('112344','3425232342'));
	}
	/**
	 * 设置User setQq() 正确的传参类型,期望返回空
	 */
	public function testUserSetQqCorectTypeNotNumber(){
		//赋值
		$this->stub->setQq('21312321sss');
		//期望得到正确的返回传参
		$this->assertEquals('',$this->stub->getQq());
	}
	//CreateTime
	/**
	 * 设置 User setCreateTime() 正确的传参类型,期望传值正确
	 */
	public function testUserSetCreateTimeCorrectType(){
		$timeStamp = time();
		//赋值int
		$this->stub->setCreateTime($timeStamp);
		//期望得到正确的返回传参
		$this->assertEquals($timeStamp,$this->stub->getCreateTime());
	}

	/**
	 * 设置 User setCreateTime() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetCreateTimeWrongType(){
		//string
		$this->stub->setCreateTime('createTime');
	}

	/**
	 * 设置 User setCreateTime() 错误的传参类型,但是传参是数值
	 */
	public function testUserSetCreateTimeWrongTypeButNumeric(){
		$timeStamp = time();

		$this->stub->setCreateTime((string)$timeStamp);
		$this->assertTrue(is_int($this->stub->getCreateTime()));
		$this->assertEquals($timeStamp,$this->stub->getCreateTime());
	}

	//Status
	/**
	 * 循环测试user setStatus() 是否符合预订范围
	 * 
     * @dataProvider UserStatusProvider
     */
    public function testUserSetStatus($status,$expected){

    	$this->stub->setStatus($status);
        $this->assertEquals($this->stub->getStatus(),$expected);
    }

    /**
     * user Status 数据构造器
     */
	public function UserStatusProvider(){
        return array(
          array(USER_STATUS_NORMAL, USER_STATUS_NORMAL),
          array(USER_STATUS_BANNED, USER_STATUS_BANNED),
          array(999, USER_STATUS_NORMAL)
        );
    }

	/**
	 * 设置 User setStatus() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetStatusWrongType(){
		//array
		$this->stub->setStatus('status');
	}

	//LastScore
	/**
	 * 设置 User setLastScore() 正确的传参类型,期望传值正确
	 */
	public function testUserSetLastScoreCorrectType(){
		//赋值string
		$this->stub->setLastScore("1231231232");
		//期望得到正确的返回传参
		$this->assertEquals("1231231232",$this->stub->getLastScore());
	}
	/**
	 * 设置 User setLastScore() 错误的传参类型,期望抛出TypeError exception
	 * 
	 * @expectedException TypeError
	 */
	public function testUserSetLastScoreWrongType(){
		//array
		$this->stub->setLastScore(array('112344','3425232342'));
	}

	//IsVip
	/**
	 * 设置User setIsVip() 正确的传参类型,期望传值正确
	 */
	public function testUserSetIsVipCorrectType(){
		//赋值int
		$this->stub->setIsVip(1);
		//期望得到正确的返回传参
		$this->assertEquals(1,$this->stub->getIsVip());
	}

	/**
	 * 设置User setIsVip() 错误的传参类型,但是传参是数值
	 */
	public function testUserSetIsVipWrongTypeButNumeric(){
		$this->stub->setIsVip('1');
		$this->assertTrue(is_bool($this->stub->getIsVip()));
		$this->assertEquals(1,$this->stub->getIsVip());
	}
}