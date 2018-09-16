<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Cron;

use Alaa\DynamicFrontName\Exception\FrontNameCreateException;
use Alaa\DynamicFrontName\Logger\Logger;
use Alaa\DynamicFrontName\Model\FrontNameAction;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\FrameWork\Event\ManagerInterface;

/**
 * Class FrontNameGenerator
 *
 * @package Alaa\DynamicFrontName\Cron
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameGenerator
{
    /**
     * @var FrontNameAction
     */
    protected $frontNameAction;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * FrontNameGenerator constructor.
     *
     * @param FrontNameAction      $frontNameAction
     * @param Logger               $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param ManagerInterface     $eventManager
     */
    public function __construct(
        FrontNameAction $frontNameAction,
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        ManagerInterface $eventManager
    ) {
        $this->frontNameAction = $frontNameAction;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
    }

    /**
     * Change admin front name
     */
    public function run()
    {
        if ($this->scopeConfig->isSetFlag('dynamic_frontname/settings/enabled')) {
            try {
                $frontName = $this->frontNameAction->changeFrontName();
                if (null !== $frontName) {
                    $this->eventManager
                        ->dispatch('backend_front_name_changed', ['front_name' => $frontName]);
                }
            } catch (FrontNameCreateException $e) {
                $this->logger->critical($e->getMessage());
            }
        }
    }
}
