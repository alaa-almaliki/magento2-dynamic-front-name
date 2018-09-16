<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Model;

use Alaa\DynamicFrontName\Model\FrontName;
use Alaa\DynamicFrontName\Model\FrontNameBuilder;
use Alaa\DynamicFrontName\Model\FrontNameInterfaceFactory;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\Config;
use Magento\Framework\Math\Random;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class FrontNameBuilderTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameBuilderTest extends TestCase
{
    use MockTrait;

    /**
     * @var \Alaa\DynamicFrontName\Model\FrontNameBuilder
     */
    private $subject;

    /**
     * @var FrontNameInterfaceFactory|MockObject
     */
    private $frontNameFactory;

    /**
     * @var Random|MockObject
     */
    private $mathRandom;

    /**
     * @var Config|MockObject
     */
    private $scopeConfig;

    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfig = $this->getMock(Config::class, ['getValue']);
        $this->frontNameFactory = $this->getMock(FrontNameInterfaceFactory::class, ['create']);
        $this->subject = $objectManager->getObject(
            FrontNameBuilder::class,
            [
                'scopeConfig' => $this->scopeConfig,
                'frontNameFactory' => $this->frontNameFactory
            ]
        );
    }

    public function testBuildFrontName()
    {
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn(8);

        $this->frontNameFactory->expects($this->any())
            ->method('create')
            ->willReturn(new FrontName('admin123'));

        $this->assertEquals(8, \strlen((string) $this->subject->buildFrontName()));
        $this->assertEquals('admin123', (string) $this->subject->buildFrontName());
    }
}
