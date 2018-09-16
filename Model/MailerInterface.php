<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

/**
 * Interface MailerInterface
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki
 */
interface MailerInterface
{
    /**
     * @return void
     */
    public function sendMail();
}
