<?php
namespace Member\Adapter\User;

use tests\GenericTestCase;
use Member\Translator\UserDataBaseTranslator;
use Member\Model\User;
use Member\Adapter\User\Query\UserRowCacheQuery;
use Member\Utils\ObjectGenerate;

use Prophecy\Argument;

class UserDataBaseAdapterTest extends GenericTestCase
{
    public function setUp()
    {
        $this->userRowCacheQuery = $this->prophesize(UserRowCacheQuery::class);
        $this->userDataBaseTranslator = $this->prophesize(UserDataBaseTranslator::class);
    }

    public function tearDown()
    {
        unset($this->userRowCacheQuery);
        unset($this->userDataBaseTranslator);
    }

    public function testConstructor()
    {
        $adapter = new UserDataBaseAdapter();
        $userRowCacheQueryParameter = $this->getPrivateProperty(
            'Member\Adapter\User\UserDataBaseAdapter',
            'userRowCacheQuery'
        );
        $this->assertInstanceOf(
            'Member\Adapter\User\Query\UserRowCacheQuery',
            $userRowCacheQueryParameter->getValue($adapter)
        );

        $userDataBaseTranslatorParameter = $this->getPrivateProperty(
            'Member\Adapter\User\UserDataBaseAdapter',
            'userDataBaseTranslator'
        );
        $this->assertInstanceOf(
            'Member\Translator\UserDataBaseTranslator',
            $userDataBaseTranslatorParameter->getValue($adapter)
        );
    }

    public function testImplementsIUserAdapter()
    {
        $adapter = new UserDataBaseAdapter();
        $this->assertInstanceOf('Member\Adapter\User\IUserAdapter', $adapter);
    }

    public function testAddSuccess()
    {
        $lastInsertId = 5;

        $user = $this->getMockBuilder(User::class)
                     ->setMethods(['setId'])
                     ->getMock();
        $user->expects($this->once())->method('setId')->with($this->equalTo($lastInsertId));

        $userTranslator = new UserDataBaseTranslator();
        $userInfo = $userTranslator->objectToArray($user);

        $this->userDataBaseTranslator->objectToArray(Argument::exact($user))
                                    ->shouldBeCalledTimes(1)
                                    ->willReturn($userInfo);
        $this->userRowCacheQuery->add(Argument::exact($userInfo))
                                ->willReturn($lastInsertId)
                                ->shouldBeCalledTimes(1);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->once())
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->once())
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->add($user);
        $this->assertTrue($result);
    }

    public function testAddFailure()
    {
        $user = $this->getMockBuilder(User::class)
                     ->setMethods(['setId'])
                     ->getMock();
        $user->expects($this->exactly(0))->method('setId');

        $userTranslator = new UserDataBaseTranslator();
        $userInfo = $userTranslator->objectToArray($user);

        $this->userDataBaseTranslator->objectToArray(Argument::exact($user))
                                     ->shouldBeCalledTimes(1)
                                     ->willReturn($userInfo);
        $this->userRowCacheQuery->add(Argument::exact($userInfo))
                                ->willReturn(0)
                                ->shouldBeCalledTimes(1);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->once())
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->once())
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->add($user);

        $this->assertEquals(0, $user->getId());
        $this->assertFalse($result);
    }

    public function testUpdateSuccess()
    {
        $user = ObjectGenerate::generateUser(1);
        $modifyKeys = array('nickName','realName');

        $userTranslator = new UserDataBaseTranslator();
        $userInfo = $userTranslator->objectToArray($user);

        $userRowCacheQuery = new UserRowCacheQuery();

        $this->userDataBaseTranslator->objectToArray(Argument::exact($user), Argument::exact($modifyKeys))
                                     ->shouldBeCalledTimes(1)->willReturn($userInfo);
        $this->userRowCacheQuery->getPrimaryKey()
                                ->shouldBeCalledTimes(1)
                                ->willReturn($userRowCacheQuery->getPrimaryKey());

        $this->userRowCacheQuery->update(
            Argument::exact($userInfo),
            Argument::exact(array($userRowCacheQuery->getPrimaryKey()=>$user->getId()))
        )->shouldBeCalledTimes(1)->willReturn(true);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->once())
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(2))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->update($user, $modifyKeys);

        $this->assertTrue($result);
    }

    public function testUpdateFailure()
    {

        $user = ObjectGenerate::generateUser(1);
        $modifyKeys = array('nickName','realName');

        $userTranslator = new UserDataBaseTranslator();
        $userInfo = $userTranslator->objectToArray($user);

        $userRowCacheQuery = new UserRowCacheQuery();

        $this->userDataBaseTranslator->objectToArray(Argument::exact($user), Argument::exact($modifyKeys))
                                     ->shouldBeCalledTimes(1)->willReturn($userInfo);
        $this->userRowCacheQuery->getPrimaryKey()
                                ->shouldBeCalledTimes(1)
                                ->willReturn($userRowCacheQuery->getPrimaryKey());

        $this->userRowCacheQuery->update(
            Argument::exact($userInfo),
            Argument::exact(array($userRowCacheQuery->getPrimaryKey()=>$user->getId()))
        )->shouldBeCalledTimes(1)->willReturn(false);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->once())
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(2))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->update($user, $modifyKeys);

        $this->assertFalse($result);
    }

    public function testGetOneNotExist()
    {
        $userId = 1;
        $adapter = new UserDataBaseAdapter();

        $this->userRowCacheQuery->getOne(Argument::exact($userId))->shouldBeCalledTimes(1)->willReturn(array());
        $this->userDataBaseTranslator->arrayToObject()->shouldNotBeCalled();

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->exactly(0))
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->getOne($userId);
        $this->assertInstanceOf('Member\Model\NullUser', $result);
    }

    public function testGetOneExist()
    {
        $userId = 1;
        $adapter = new UserDataBaseAdapter();

        $user = ObjectGenerate::generateUser(1);
        $userDataBaseTranslator = new UserDataBaseTranslator();
        $userInfo = $userDataBaseTranslator->objectToArray($user);

        $this->userRowCacheQuery->getOne(Argument::exact($userId))
                                ->shouldBeCalledTimes(1)
                                ->willReturn($userInfo);
        $this->userDataBaseTranslator->arrayToObject(Argument::exact($userInfo))
                                     ->shouldBeCalledTimes(1)
                                     ->willReturn($user);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->exactly(1))
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->getOne($userId);
        $this->assertSame($user, $result);
    }

    public function testGetListNotExist()
    {
        $userIds = array(1,2,3);
        $adapter = new UserDataBaseAdapter();

        $this->userRowCacheQuery->getList(Argument::exact($userIds))->shouldBeCalledTimes(1)->willReturn(array());
        $this->userDataBaseTranslator->arrayToObject()->shouldNotBeCalled();

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->exactly(0))
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->getList($userIds);
        $this->assertEmpty($result);
    }

    public function testGetListExist()
    {
        $userIds = array(1,2,3);
        $adapter = new UserDataBaseAdapter();
        $userDataBaseTranslator = new UserDataBaseTranslator();
        $userObjects = $userInfoList = array();

        foreach ($userIds as $userId) {
            $user = ObjectGenerate::generateUser($userId);
            $userInfo = $userDataBaseTranslator->objectToArray($user);
            $userObjects[] = $user;
            $userInfoList[] = $userInfo;
            $this->userDataBaseTranslator->arrayToObject($userInfo)->shouldBeCalledTimes(1)->willReturn($user);
        }

        $this->userRowCacheQuery->getList(Argument::exact($userIds))->shouldBeCalledTimes(1)->willReturn($userInfoList);

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->once())
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->getList($userIds);
        $this->assertSame($userObjects, $result);
    }

    public function testFilterWithEmptyResult()
    {
        $this->userRowCacheQuery->find(
            Argument::exact(' 1 '),
            Argument::exact(0),
            Argument::exact(20)
        )->shouldBeCalledTimes(1)->willReturn(array());

        $this->userRowCacheQuery->getPrimaryKey()->shouldNotBeCalled();
        $this->userRowCacheQuery->count(Argument::type('string'))->shouldNotBeCalled();

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getList'])
                        ->getMock();
        $adapter->expects($this->exactly(0))
                ->method('getList');

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getUserDataBaseTranslator'])
                        ->getMock();
        $adapter->expects($this->exactly(0))
            ->method('getUserDataBaseTranslator')
            ->willReturn($this->userDataBaseTranslator->reveal());
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());

        $result = $adapter->filter();
        $this->assertEmpty($result);
    }

    private function generateUserIds(int $number = 20) : array
    {
        $userRowCacheQuery = new UserRowCacheQuery();
        $primaryKey = $userRowCacheQuery->getPrimaryKey();

        $userIds = $userIdsFormat = array();

        for ($i=0; $i<$number; $i++) {
            $userIds[] = $i;
            $userIdsFormat[] = [$primaryKey=>$i];
        }

        return array($userIds, $userIdsFormat);
    }

    public function testFilterWithEmptyFilterCondition()
    {
        $filter = $sort = array();

        $userRowCacheQuery = new UserRowCacheQuery();
        $primaryKey = $userRowCacheQuery->getPrimaryKey();
        list($userIds, $userIdsFormat) = $this->generateUserIds();

        $this->userRowCacheQuery->find(
            Argument::exact(' 1 '),
            Argument::exact(0),
            Argument::exact(20)
        )->shouldBeCalledTimes(1)->willReturn($userIdsFormat);

        $this->userRowCacheQuery->getPrimaryKey()->shouldBeCalledTimes(1)->willReturn($primaryKey);
        $this->userRowCacheQuery->count(Argument::exact(' 1 '))->shouldBeCalledTimes(1)->willReturn(sizeof($userIds));

        $adapter = $this->getMockBuilder(UserDataBaseAdapter::class)
                        ->setMethods(['getUserRowCacheQuery', 'getList'])
                        ->getMock();
        $adapter->expects($this->exactly(1))
            ->method('getUserRowCacheQuery')
            ->willReturn($this->userRowCacheQuery->reveal());
        $adapter->expects($this->exactly(1))
                ->method('getList')
                ->with($this->equalTo($userIds))
                ->will($this->returnArgument(0));

        
        list($userList, $count) = $adapter->filter();

        $this->assertEquals($userIds, $userList);
        $this->assertEquals(sizeof($userIds), $count);
    }
}
