<?php

namespace App\Abstracts;

use PDO;
use PDOException;

abstract class AbstractDatabaseConnection
{
    private ?PDO $connection;

    /**
     * @param int $errorMode
     * @param int $fetchMode
     * @param bool $emulate
     */
    public function __construct(
        int $errorMode = PDO::ERRMODE_EXCEPTION,
        int $fetchMode = PDO::FETCH_ASSOC,
        bool $emulate = false
    ) {
        $config = $this->getconfig();

        $this->connection = $this->connect(
            $config['user'],
            $config['pass'],
            $config['host'],
            $config['database'],
            $config['charset'],
            $config['port'],
            [
                PDO::ATTR_ERRMODE => $errorMode,
                PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode,
                PDO::ATTR_EMULATE_PREPARES => $emulate
            ]
        );
    }

    /**
     * @return PDO|null
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    /**
     * Connect the database with provided credentials.
     *
     * @param string $user
     * @param string $pass
     * @param string $host
     * @param string $dbname
     * @param string $charset
     * @param string $port
     * @param array $options
     * @return null|PDO
     * @throws PDOException
     */
    abstract protected function connect(
        string $user,
        string $pass,
        string $host,
        string $dbname,
        string $charset,
        string $port,
        array $options = []
    ): ?PDO;

    /**
     * provide database connection configurations
     *
     * @return array
     */
    abstract protected function getConfig(): array;
}