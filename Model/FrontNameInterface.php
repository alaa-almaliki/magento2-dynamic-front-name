<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

/**
 * Interface FrontNameInterface
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface FrontNameInterface
{
    const DEFAULT_LENGTH = 8;

    /**
     * @return string
     */
    public function getFrontName(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
