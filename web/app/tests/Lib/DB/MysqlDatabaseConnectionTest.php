<?php

namespace Lib\DB;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Lib\DB\MysqlDatabaseConnection;


class MysqlDatabaseConnectionTest extends TestCase
{
    public function testSuccessfulConnectionWithParams(): void
    {
        $config = [
            'host' => 'mysql',
            'database' => 'test',
            'port' => '3306',
            'user' => 'root',
            'pass' => 'root',
            'charset' => 'utf8mb4'
        ];
        $connection = new MysqlDatabaseConnection($config);
        $this->assertIsArray($connection->config);
        $this->assertArrayHasKey('host', $connection->config);
        $this->assertArrayHasKey('database', $connection->config);
        $this->assertArrayHasKey('port', $connection->config);
        $this->assertArrayHasKey('user', $connection->config);
        $this->assertArrayHasKey('pass', $connection->config);
        $this->assertArrayHasKey('charset', $connection->config);
        $this->assertInstanceOf(PDO::class, $connection->connection);
    }

    public function testSuccessfulConnectionWithConfig(): void
    {
        $connection = new MysqlDatabaseConnection();
        $this->assertIsArray($connection->config);
        $this->assertArrayHasKey('host', $connection->config);
        $this->assertArrayHasKey('database', $connection->config);
        $this->assertArrayHasKey('port', $connection->config);
        $this->assertArrayHasKey('user', $connection->config);
        $this->assertArrayHasKey('pass', $connection->config);
        $this->assertArrayHasKey('charset', $connection->config);
        $this->assertInstanceOf(PDO::class, $connection->connection);
    }


    public function testUnsuccessfulConnection(): void
    {
        $config = [
            'host' => 'mysql',
            'database' => 'oscar',
            'port' => '3306',
            'user' => 'root',
            'pass' => 'root',
            'charset' => 'utf8mb4'
        ];
        $this->expectException(\PDOException::class);
       $pdo =  new MysqlDatabaseConnection($config);
    }

}
