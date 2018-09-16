<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Cron;

use Alaa\DynamicFrontName\Cron\FrontNameGenerator;
use Alaa\DynamicFrontName\Exception\FrontNameCreateException;
use Alaa\DynamicFrontName\Logger\Logger;
use Alaa\DynamicFrontName\Model\FrontNameAction;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Manager;
use Magento\Framework\Event\ManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class FrontNameGeneratorTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameGeneratorTest extends TestCase
{
    use MockTrait;

    /**
     * @var FrontNameGenerator|MockObject
     */
    private $subject;

    /**
     * @var FrontNameAction|MockObject
     */
    private $frontNameAction;

    /**
     * @var Logger|MockObject
     */
    private $logger;

    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;

    /**
     * @var ManagerInterface|MockObject
     */
    private $eventManager;

    public function setUp()
    {
        $this->frontNameAction = $this->getMock(FrontNameAction::class, ['changeFrontName']);
        $this->logger = $this->getMock(Logger::class, ['critical']);
        $this->scopeConfig = $this->getMock(Config::class, ['isSetFlag']);
        $this->eventManager = $this->getMock(Manager::class, ['dispatch']);
        $this->subject = new FrontNameGenerator(
            $this->frontNameAction,
            $this->logger,
            $this->scopeConfig,
            $this->eventManager
        );
    }

    public function testRunNotEnabled()
    {
        $this->scopeConfig->expects($this->any())
            ->method('isSetFlag')
            ->willReturn(false);
        $this->subject->run();
    }

    public function testRunEnabled()
    {
        $this->scopeConfig->expects($this->any())
            ->method('isSetFlag')
            ->willReturn(true);

        $this->subject->run();
    }

    public function testRunThrowException()
    {
        $this->scopeConfig->expects($this->any())
            ->method('isSetFlag')
            ->willReturn(true);

        $this->frontNameAction->expects($this->any())
            ->method('changeFrontName')
            ->willThrowException(new FrontNameCreateException('Could not create Front Name for Admin'));

        $this->subject->run();
    }
}
