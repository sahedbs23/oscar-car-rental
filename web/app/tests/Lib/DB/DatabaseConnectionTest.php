<?php
namespace Tests\Lib\DB;

use Faker\Factory;
use Faker\Generator;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Lib\DB\DatabaseConnection;


class DatabaseConnectionTest extends TestCase
{
    private ?DatabaseConnection $connection;

    private ?Generator $generator;

    protected function setUp() :void
    {
        $this->connection = new DatabaseConnection();
        $this->generator = Factory::create();
    }

    protected function tearDown() :void
    {
        $this->connection = null;
        $this->generator = null;
    }

    public function test__construct(): void
    {
        $this->assertInstanceOf(DatabaseConnection::class, $this->connection);
    }

    public function test_successful_connect() : void
    {
        $instance = $this->connection->connect(
            $this->connection->getUser(),
            $this->connection->getPassword(),
            $this->connection->getHost(),
            $this->connection->getDb(),
            $this->connection->getCharset(),
            $this->connection->getPort(),
            $this->connection->getOptions()
        );

        $this->assertInstanceOf(PDO::class, $instance);

        $this->connection->setHost($this->generator->ipv4());

//        $this->expectException(\PDOException::class);
//        $this->connection->connect(
//            $this->connection->getUser(),
//            $this->connection->getPassword(),
//            $this->connection->getHost(),
//            $this->connection->getDb(),
//            $this->connection->getCharset(),
//            $this->connection->getPort(),
//            $this->connection->getOptions()
//        );
    }


}
