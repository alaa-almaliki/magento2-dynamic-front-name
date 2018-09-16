<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Model;

use Alaa\DynamicFrontName\Model\FrontName;
use Alaa\DynamicFrontName\Model\FrontNameAction;
use Alaa\DynamicFrontName\Model\FrontNameBuilder;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class FrontNameActionTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameActionTest extends TestCase
{
    use MockTrait;

    /**
     * @var FrontNameAction|MockObject
     */
    private $subject;

    /**
     * @var Writer|MockObject
     */
    private $writer;

    /**
     * @var FrontNameBuilder|MockObject
     */
    private $frontNameBuilder;

    public function setUp()
    {
        $this->writer = $this->getMock(Writer::class, ['saveConfig']);
        $this->frontNameBuilder = $this->getMock(FrontNameBuilder::class, ['buildFrontName']);
        $this->subject = new FrontNameAction($this->writer, $this->frontNameBuilder);
    }

    public function testChangeFrontName()
    {
        $this->frontNameBuilder->expects($this->any())
            ->method('buildFrontName')
            ->willReturn(new FrontName('admin'));

        $this->assertEquals('admin', $this->subject->changeFrontName());
    }

    public function testChangeFrontNameNull()
    {
        $this->frontNameBuilder->expects($this->any())
            ->method('buildFrontName')
            ->willReturn(new FrontName(''));

        $this->assertEquals(null, $this->subject->changeFrontName());
    }

    /**
     * @expectedException \Alaa\DynamicFrontName\Exception\FrontNameCreateException
     */
    public function testChangeFrontNameThrowLocalizedException()
    {
        $this->frontNameBuilder->expects($this->any())
            ->method('buildFrontName')
            ->willThrowException(new LocalizedException(__('Could not create Front Name for Admin')));

        $this->subject->changeFrontName();
    }

    /**
     * @expectedException \Alaa\DynamicFrontName\Exception\FrontNameCreateException
     */
    public function testChangeFrontNameThrowFileSystemException()
    {
        $this->frontNameBuilder->expects($this->any())
            ->method('buildFrontName')
            ->willReturn(new FrontName('admin'));

        $this->writer->expects($this->any())
            ->method('saveConfig')
            ->willThrowException(new FileSystemException(__('Could not create Front Name for Admin')));

        $this->subject->changeFrontName();
    }
}
