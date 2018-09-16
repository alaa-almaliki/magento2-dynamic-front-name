<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Model;

use Magento\Framework\App\ResourceConnection;

/**
 * Class AdminMailList
 *
 * @package Alaa\DynamicFrontName\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class AdminMailList implements AdminMailListInterface
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * AdminEmailList constructor.
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @return array
     */
    public function getEmails(): array
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from('admin_user', ['email'])
            ->columns(['name' => new \Zend_Db_Expr('CONCAT(firstname, " ", lastname)')])
            ->where('is_active = ?', 1);

        return $connection->fetchAssoc($select);
    }
}
