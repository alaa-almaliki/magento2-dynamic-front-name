<?php declare(strict_types=1);

namespace Alaa\DynamicFrontName\Test\Unit\Model;

use Alaa\DynamicFrontName\Model\AdminMailList;
use Alaa\DynamicFrontName\Test\Unit\MockTrait;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class AdminMailListTest
 *
 * @package Alaa\DynamicFrontName\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class AdminMailListTest extends TestCase
{
    use MockTrait;

    /**
     * @var AdminMailList
     */
    private $subject;

    /**
     * @var Mysql|MockObject
     */
    private $connection;

    /**
     * @var ResourceConnection|MockObject
     */
    private $resourceConnection;

    /**
     * @var Select|MockObject
     */
    private $select;

    public function setUp()
    {
        $this->resourceConnection = $this->getMock(ResourceConnection::class, ['getConnection']);
        $this->select = $this->getMock(Select::class, ['from', 'columns', 'where']);
        $this->connection = $this->getMock(Mysql::class, ['fetchAssoc', 'select']);
        $this->resourceConnection->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($this->select);
        $this->select->expects($this->any())
            ->method('from')
            ->willReturnSelf();
        $this->select->expects($this->any())
            ->method('columns')
            ->willReturnSelf();
        $this->select->expects($this->any())
            ->method('where')
            ->willReturnSelf();

        $this->subject = new AdminMailList($this->resourceConnection);
    }

    public function testGetEmails()
    {
        $this->connection->expects($this->any())
            ->method('fetchAssoc')
            ->willReturn([]);
        $this->assertTrue(\is_array($this->subject->getEmails()));
    }
}
