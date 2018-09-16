<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Observer;

use Alaa\DynamicFrontName\Exception\FrontNameCreateException;
use Alaa\DynamicFrontName\Logger\Logger;
use Alaa\DynamicFrontName\Model\MailerInterfaceFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class SendMail
 *
 * @package Alaa\DynamicFrontName\Observer
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SendMail implements ObserverInterface
{
    /**
     * @var State
     */
    protected $appState;

    /**
     * @var MailerInterfaceFactory
     */
    protected $mailerFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * SendMail constructor.
     *
     * @param State                  $appState
     * @param MailerInterfaceFactory $mailerFactory
     * @param Logger                 $logger
     */
    public function __construct(
        State $appState,
        MailerInterfaceFactory $mailerFactory,
        Logger $logger
    ) {
        $this->appState = $appState;
        $this->mailerFactory = $mailerFactory;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $frontName = $observer->getEvent()->getData('front_name');
        $mailer = $this->mailerFactory->create(['frontName' => $frontName]);

        try {
            $this->sendMail([$mailer, 'sendMail']);
        } catch (FrontNameCreateException $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param $callback
     * @throws FrontNameCreateException
     */
    private function sendMail($callback)
    {
        try {
            $this->appState->emulateAreaCode(Area::AREA_FRONTEND, $callback);
        } catch (\Exception $e) {
            throw new FrontNameCreateException(
                \sprintf('Canout emulate area %s. %s', Area::AREA_FRONTEND, $e->getMessage()),
                0,
                $e
            );
        }
    }
}
