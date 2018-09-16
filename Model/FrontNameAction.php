<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

use Alaa\DynamicFrontName\Exception\FrontNameCreateException;
use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class FrontNameAction
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class FrontNameAction
{
    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @var FrontNameBuilder
     */
    protected $frontNameBuilder;

    /**
     * FrontNameAction constructor.
     *
     * @param Writer           $writer
     * @param FrontNameBuilder $frontNameBuilder
     */
    public function __construct(
        Writer $writer,
        FrontNameBuilder $frontNameBuilder
    ) {
        $this->writer = $writer;
        $this->frontNameBuilder = $frontNameBuilder;
    }

    /**
     * @return null|string
     * @throws FrontNameCreateException
     */
    public function changeFrontName()
    {
        $exception = null;

        try {
            $frontName = (string) $this->frontNameBuilder->buildFrontName();
            if (is_string($frontName) && strlen($frontName) > 0) {
                $this->writer->saveConfig(
                    [
                    'app_env' => [
                        'backend' => [
                            'frontName' => $frontName,
                        ]
                    ]
                    ]
                );

                return $frontName;
            }
        } catch (FileSystemException $e) {
            $exception = $e;
        } catch (LocalizedException $e) {
            $exception = $e;
        }

        if (null !== $exception) {
            throw new FrontNameCreateException(
                \sprintf('Could not create Front Name for Admin. %s', $exception->getMessage()),
                1,
                $exception
            );
        }

        return null;
    }
}
