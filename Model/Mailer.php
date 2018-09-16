<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

use Alaa\DynamicFrontName\Logger\Logger;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Mailer
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Mailer implements MailerInterface
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var AdminMailListInterface
     */
    protected $adminMailList;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $frontName;

    /**
     * Mailer constructor.
     *
     * @param TransportBuilder       $transportBuilder
     * @param AdminMailListInterface $adminMailList
     * @param StoreManagerInterface  $storeManager
     * @param ScopeConfigInterface   $scopeConfig
     * @param Logger                 $logger
     * @param string                 $frontName
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        AdminMailListInterface $adminMailList,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
        string $frontName
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->adminMailList    = $adminMailList;
        $this->storeManager     = $storeManager;
        $this->scopeConfig      = $scopeConfig;
        $this->logger           = $logger;
        $this->frontName        = $frontName;
    }

    /**
     * @inheritdoc
     */
    public function sendMail()
    {
        if (is_string($this->frontName) && strlen($this->frontName) > 0) {
            try {
                $adminStore = $this->storeManager->getStore(Store::ADMIN_CODE);
                $mailList = $this->adminMailList->getEmails();
                $from = $this->resolveFromEmail($mailList);
                $templateVars = ['url' =>  $adminStore->getUrl() . '/' . $this->frontName];
                $this->transportBuilder->setTemplateIdentifier('new_backend_url');
                $this->transportBuilder->setTemplateOptions(
                    [
                            'area' => Area::AREA_FRONTEND,
                            'store' => $this->storeManager->getDefaultStoreView()->getId()
                        ]
                );
                $this->transportBuilder->setTemplateVars($templateVars);
                $this->transportBuilder->setFrom($from);

                foreach ($mailList as $mail) {
                    $this->transportBuilder->addTo($mail['email'], $mail['name']);
                }

                $this->transportBuilder->getTransport()->sendMessage();
            } catch (MailException $e) {
                $this->logger->critical(__('Could not send email. %s', $e->getMessage()));
            }
        }
    }

    /**
     * @param array $mailList
     * @return array
     */
    private function resolveFromEmail(array $mailList): array
    {
        $configFrom = $this->scopeConfig
            ->getValue('dynamic_frontname/settings/sender_email');

        if (!!$configFrom === false) {
            return \current($mailList);
        }

        return [
            'email' => $configFrom,
            'name' => $this->getEmailUserName($configFrom)
        ];
    }

    /**
     * @param string $email
     * @return string
     */
    private function getEmailUserName(string $email): string
    {
        $username = '';
        if (\strpos($email, '@') !== false) {
            $username = \explode('@', $email)[0];
        }

        return $username;
    }
}
