<?php
namespace App\Abstracts;

use PDO;
use PDOException;

abstract class AbstractDatabaseConnection
{
    public array $config;

    //TODO:: Make connection protected.
    public ?PDO $connection;

    /**
     * @param array $config
     * @param int $errorMode
     * @param int $fetchMode
     * @param bool $emulate
     */
    public function __construct(
        array $config = [],
        int $errorMode = PDO::ERRMODE_EXCEPTION,
        int $fetchMode = PDO::FETCH_ASSOC,
        bool $emulate = false
    ) {
        $this->config = $this->getconfig();

        $this->connection = $this->connect(
            $this->config['user'],
            $this->config['pass'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset'],
            $this->config['port'],
            [
                PDO::ATTR_ERRMODE => $errorMode,
                PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode,
                PDO::ATTR_EMULATE_PREPARES => $emulate
            ]
        );
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
    ):?PDO;

    /**
     * provide database connection configurations
     *
     * @return array
     */
    abstract protected function getConfig():array;
}