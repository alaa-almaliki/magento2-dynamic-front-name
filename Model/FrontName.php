<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

/**
 * Class FrontName
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontName implements FrontNameInterface
{
    /**
     * @var string
     */
    protected $frontName;

    /**
     * FrontName constructor.
     *
     * @param string $frontName
     */
    public function __construct(string $frontName)
    {
        $this->frontName = $frontName;
    }

    /**
     * @return string
     */
    public function getFrontName(): string
    {
        return $this->frontName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFrontName();
    }
}
