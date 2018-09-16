<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Observer;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FlushConfigCache
 *
 * @package Alaa\DynamicFrontName\Observer
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FlushConfigCache implements ObserverInterface
{
    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var Pool
     */
    protected $cachePool;

    /**
     * FlushConfigCache constructor.
     *
     * @param TypeListInterface $cacheTypeList
     * @param Pool              $cachePool
     */
    public function __construct(TypeListInterface $cacheTypeList, Pool $cachePool)
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->cachePool = $cachePool;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->cacheTypeList->cleanType('config');
        /** @var \Magento\Framework\Cache\Frontend\Decorator\Logger $cache */
        while ($cache = $this->cachePool->current()) {
            $cache->getBackend()->clean();
            $this->cachePool->next();
        }
    }
}
