<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Observer;

use Alaa\DynamicFrontName\Observer\FlushConfigCache;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class FlushConfigCacheTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Observer
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FlushConfigCacheTest extends TestCase
{
    public function testExecute()
    {
        $objectManager = new ObjectManager($this);
        $subject = $objectManager->getObject(FlushConfigCache::class);
        $subject->execute(new \Magento\Framework\Event\Observer());
    }
}
