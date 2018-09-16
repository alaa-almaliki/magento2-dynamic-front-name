<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

/**
 * Interface AdminMailListInterface
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface AdminMailListInterface
{
    /**
     * @return array
     */
    public function getEmails(): array;
}
