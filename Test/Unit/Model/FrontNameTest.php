<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Model;

use Alaa\DynamicFrontName\Model\FrontName;
use PHPUnit\Framework\TestCase;

/**
 * Class FrontNameTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameTest extends TestCase
{
    public function testFrontName()
    {
        $frontName = new FrontName('admin');
        $this->assertEquals('admin', $frontName);
    }
}
