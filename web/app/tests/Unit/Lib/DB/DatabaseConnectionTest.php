<?php
namespace Lib\DB;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Lib\DB\DatabaseConnection;

class DatabaseConnectionTest extends TestCase
{
    private ?DatabaseConnection $connection;

    protected function setUp() :void
    {
        $this->connection = new DatabaseConnection();
    }

    protected function tearDown() :void
    {
        $this->connection = null;
    }

    public function test__construct(): void
    {
        //$this->assertInstanceOf(DatabaseConnection::class, $this->connection);
    }

    public function testConnect() : void
    {
        //$this->assertInstanceOf(PDO::class, $this->connection->connection);
    }


}
