<?php
namespace mikemix\Wiziq\Tests\API;

use mikemix\Wiziq\API\ClassroomApi;
use mikemix\Wiziq\API\Gateway;
use mikemix\Wiziq\API\Request;
use mikemix\Wiziq\Common\Api\ClassroomApiInterface;
use mikemix\Wiziq\Entity\Classroom;

class ClassroomApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var Gateway|\PHPUnit_Framework_MockObject_MockObject */
    private $gateway;

    /** @var ClassroomApi */
    private $sdk;

    public function setUp()
    {
        $this->gateway = $this->getMockBuilder(Gateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest'])
            ->getMock();

        $this->sdk = new ClassroomApi($this->gateway);
    }

    public function testInterface()
    {
        $this->assertInstanceOf(ClassroomApiInterface::class, $this->sdk);
    }

    public function testCreateClassroom()
    {
        $classroom = Classroom::build('Title', 'mike@test.com', new \DateTime('2015-12-30 12:30:50'));

        $this->gateway->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo(new Request\Create($classroom)))
            ->will($this->returnValue(
                simplexml_load_string(
                    file_get_contents(__DIR__ . '/../.resources/create-classroom-success-response.txt')
                )
            ));

        $this->assertSame([
            'class_id'      => 15716,
            'recording_url' => 'http://live.wiziq.com/aliveext/Recorded.aspx?SessionCode=pqcTxHXEgSU%3d',
            'presenters'    => [
                ['email' => 'tsb.kid@gmail.com',  'url' => 'http://live.wiziq.com/aliveext/LoginToSession.aspx?SessionCode=Mk5wx06KmZg%3d'],
                ['email' => 'tsb2.kid@gmail.com', 'url' => 'http://live.wiziq.com/aliveext/LoginToSession.aspx?SessionCode=Xxxwx06KmZg%3d'],
            ],
        ], $this->sdk->create($classroom));
    }
}