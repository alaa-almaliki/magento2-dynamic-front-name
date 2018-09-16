<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Observer;

use Alaa\DynamicFrontName\Observer\SendMail;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SendMailTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Observer
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SendMailTest extends TestCase
{
    use MockTrait;

    /**
     * @var SendMail|MockObject
     */
    private $subject;

    /**
     * @var State|MockObject
     */
    private $appState;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        /** @var State appState */
        $this->appState = $this->getMock(State::class, ['emulateAreaCode']);
    }

    public function testSendMail()
    {
        /** @var \Alaa\DynamicFrontName\Observer\SendMail|MockObject $subject */
        $subject = $this->objectManager->getObject(
            SendMail::class, [
            'appState' => $this->appState
            ]
        );

        $subject->execute(new Observer(['event' => new DataObject(['front_name' => 'admin'])]));
    }

    public function testSendMailThrowException()
    {
        $this->appState->expects($this->any())
            ->method('emulateAreaCode')
            ->willThrowException(new \Exception('can not emulate'));

        /** @var \Alaa\DynamicFrontName\Observer\SendMail|MockObject $subject */
        $subject = $this->objectManager->getObject(
            SendMail::class, [
            'appState' => $this->appState
            ]
        );

        $subject->execute(new Observer(['event' => new DataObject(['front_name' => 'admin'])]));
    }
}
