<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Model;

use Alaa\DynamicFrontName\Model\AdminMailList;
use Alaa\DynamicFrontName\Model\Mailer;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\Config;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\Transport;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class MailerTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class MailerTest extends TestCase
{
    use MockTrait;

    /**
     * @var AdminMailList|MockObject
     */
    private $adminMailList;

    /**
     * @var StoreManager|MockObject
     */
    private $storeManager;

    /**
     * @var Store|MockObject
     */
    private $store;

    /**
     * @var Config|MockObject
     */
    private $scopeConfig;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var TransportBuilder|MockObject
     */
    private $transportBuilder;

    /**
     * @var \Magento\Framework\Mail\Transport|MockObject
     */
    private $transport;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->adminMailList = $this->getMock(AdminMailList::class, ['getEmails']);
        $this->storeManager = $this->getMock(StoreManager::class, ['getStore', 'getDefaultStoreView']);
        $this->store = $this->getMock(Store::class, ['getId', 'getUrl']);
        $this->scopeConfig = $this->getMock(Config::class, ['getValue']);
        $this->transport = $this->getMock(Transport::class, ['sendMessage']);

        $this->transportBuilder = $this->getMock(
            TransportBuilder::class, [
            'setFrom',
            'addTo',
            'getTransport',
            ]
        );

        $this->transportBuilder->expects($this->any())
            ->method('getTransport')
            ->willReturn($this->transport);

        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $this->storeManager->expects($this->any())
            ->method('getDefaultStoreView')
            ->willReturn($this->store);

        $this->adminMailList->expects($this->any())
            ->method('getEmails')
            ->willReturn([['email' => 'jon.doe@example.com', 'name' => 'Jon Doe']]);

        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->store->expects($this->any())
            ->method('getUrl')
            ->willReturn('https://www.example.com');
    }

    public function testSendMailNoConfigSender()
    {
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn(null);

        $subject = $this->objectManager->getObject(
            Mailer::class, [
            'transportBuilder' => $this->transportBuilder,
            'adminMailList' => $this->adminMailList,
            'storeManager' => $this->storeManager,
            'scopeConfig' => $this->scopeConfig,
            'frontName' => 'admin'
            ]
        );

        $subject->sendMail();
    }

    public function testSendMailWithConfigSender()
    {
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn('test@test.com');

        $subject = $this->objectManager->getObject(
            Mailer::class, [
            'transportBuilder' => $this->transportBuilder,
            'adminMailList' => $this->adminMailList,
            'storeManager' => $this->storeManager,
            'scopeConfig' => $this->scopeConfig,
            'frontName' => 'admin'
            ]
        );

        $subject->sendMail();
    }

    public function testSendMailThrowsCatchedException()
    {
        $subject = $this->objectManager->getObject(
            Mailer::class, [
            'transportBuilder' => $this->transportBuilder,
            'adminMailList' => $this->adminMailList,
            'storeManager' => $this->storeManager,
            'scopeConfig' => $this->scopeConfig,
            'frontName' => 'admin'
            ]
        );

        $this->transport->expects($this->any())
            ->method('sendMessage')
            ->willThrowException(new MailException(__('Could not send email')));

        $subject->sendMail();
    }

    public function testSendMailNoFrontName()
    {
        $subject = $this->objectManager->getObject(
            Mailer::class, [
            'frontName' => ''
            ]
        );

        $subject->sendMail();
    }
}
