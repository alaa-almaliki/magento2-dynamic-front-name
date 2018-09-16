<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Logger;

use Magento\Framework\Logger\Handler\Base;

/**
 * Class Handler
 *
 * @package Alaa\DynamicFrontName\Logger
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Handler extends Base
{
    /**
     * @var int
     */
    protected $loggerType = \Monolog\Logger::INFO;

    /**
     * @var string
     */
    protected $fileName = '/var/log/dynamic-frontname.log';
}
