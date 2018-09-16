<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Math\Random;

/**
 * Class FrontNameBuilder
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameBuilder
{
    /**
     * @var FrontNameInterfaceFactory
     */
    protected $frontNameFactory;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * FrontNameBuilder constructor.
     *
     * @param FrontNameInterfaceFactory $frontNameFactory
     * @param Random                    $random
     * @param ScopeConfigInterface      $scopeConfig
     */
    public function __construct(
        FrontNameInterfaceFactory $frontNameFactory,
        Random $random,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->frontNameFactory = $frontNameFactory;
        $this->random = $random;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return FrontNameInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function buildFrontName(): FrontNameInterface
    {
        $length = (int) $this->scopeConfig
            ->getValue('dynamic_frontname/settings/length') ?: FrontNameInterface::DEFAULT_LENGTH;

        return $this->frontNameFactory->create(
            [
            'frontName' => $this->random->getRandomString($length)
            ]
        );
    }
}
