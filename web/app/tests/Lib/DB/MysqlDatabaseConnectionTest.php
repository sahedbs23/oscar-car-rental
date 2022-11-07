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
            'database' => 'oscar',
            'port' => '3306',
            'user' => 'root',
            'pass' => 'root',
            'charset' => 'utf8mb4'
        ];
        $connection = new MysqlDatabaseConnection($config);
        $this->assertInstanceOf(PDO::class, $connection->getConnection());
    }

    public function testSuccessfulConnectionWithConfig(): void
    {
        $connection = new MysqlDatabaseConnection();
        $this->assertInstanceOf(PDO::class, $connection->getConnection());
    }


    public function testUnsuccessfulConnection(): void
    {
        $config = [
            'host' => 'mysql',
            'database' => 'test',
            'port' => '3306',
            'user' => 'root',
            'pass' => 'root',
            'charset' => 'utf8mb4'
        ];
        $this->expectException(\PDOException::class);
        $pdo = new MysqlDatabaseConnection($config);
    }

}
