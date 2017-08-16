<?php
namespace System\Observer;

use tests\GenericTestCase;
use System\Interfaces\Observer;

class SubjectTest extends GenericTestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = new Subject();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    public function testConstructor()
    {
        $observersParameter = $this->getPrivateProperty('System\Observer\Subject', 'observers');
        $this->assertInternalType('array', $observersParameter->getValue($this->subject));
        $this->assertEmpty($observersParameter->getValue($this->subject));
    }

    public function testAttachObserver()
    {
        $observerOne = $this->generateObserver();
        $observerTwo = $this->generateObserver();

        $this->subject->attach($observerOne);
        $this->subject->attach($observerTwo);
        
        $observers = $this->getPrivateProperty('System\Observer\Subject', 'observers')
                          ->getValue($this->subject);
        $this->assertEquals(2, sizeof($observers));

        $this->assertSame($observerOne, $observers[0]);
        $this->assertSame($observerTwo, $observers[1]);
    }

    public function testDetachExistObserver()
    {
        $observerOne = $this->generateObserver();
        $observerOne->method('update')->willReturn(1);
        $observerTwo = $this->generateObserver();
        $observerTwo->method('update')->willReturn(2);

        $this->subject->attach($observerOne);
        $this->subject->attach($observerTwo);

        $result = $this->subject->detach($observerOne);
        $this->assertTrue($result);

        $observers = $this->getPrivateProperty('System\Observer\Subject', 'observers')
                          ->getValue($this->subject);
        $this->assertEquals(1, sizeof($observers));
        $this->assertSame($observerTwo, $observers[1]);
    }

    public function testDetachNotExistObserver()
    {
        $observerOne = $this->generateObserver();
        $observerOne->method('update')->willReturn(1);
        $observerTwo = $this->generateObserver();
        $observerTwo->method('update')->willReturn(2);
        $observerThree = $this->generateObserver();
        $observerThree->method('update')->willReturn(3);

        $this->subject->attach($observerOne);
        $this->subject->attach($observerTwo);

        $result = $this->subject->detach($observerThree);
        $this->assertFalse($result);
    }

    public function testNotifyObserver()
    {
        $observer = $this->generateObserver();
        $observer->expects($this->once())
                ->method('update');

        $this->subject->attach($observer);
        $this->subject->notifyObserver();
    }
    
    private function generateObserver()
    {
        return $this->getMockBuilder(Observer::class)
                    ->setMethods(['update'])
                    ->getMock();
    }
}
