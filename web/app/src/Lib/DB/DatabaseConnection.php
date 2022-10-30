<?php

namespace App\Lib\DB;

use PDO;
use PDOException;

class DatabaseConnection
{
    public ?PDO $connection;

    private string $host;

    private string $port;

    private string $db;

    private string $user;

    private string $pass;

    private string $charset;

    private array $options;

    public function __construct($errorMode = PDO::ERRMODE_EXCEPTION, $fetchMode = PDO::FETCH_ASSOC, $emulate = false)
    {
        $config = include __DIR__ . '/../../config/database.php';

        $this->db = $config['database'];
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->charset = $config['charset'];

        $this->options = [
            PDO::ATTR_ERRMODE => $errorMode,
            PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode,
            PDO::ATTR_EMULATE_PREPARES => $emulate
        ];
    }

    /**
     * @return PDO|null
     * @throws PDOException
     */
    public static function databaseManager(): ?PDO
    {
        $db = new self();
        return $db->connect(
            $db->getUser(),
            $db->getPassword(),
            $db->getHost(),
            $db->getDb(),
            $db->getCharset(),
            $db->getPort(),
            $db->getOptions()
        );
    }

    /**
     * @param string $user
     * @param string $pass
     * @param string $host
     * @param string $dbname
     * @param string $charset
     * @param string $port
     * @param array $options
     * @return PDO|null
     * @throws PDOException
     */
    public function connect(
        string $user,
        string $pass,
        string $host,
        string $dbname,
        string $charset,
        string $port,
        array $options = []
    ): ?PDO {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$port";

        return new PDO($dsn, $user, $pass, $options);
    }

    /**
     * @return mixed|string
     */
    public function getHost(): mixed
    {
        return $this->host;
    }

    /**
     * @return mixed|string
     */
    public function getPort(): mixed
    {
        return $this->port;
    }

    /**
     * @return mixed|string
     */
    public function getDb(): mixed
    {
        return $this->db;
    }

    /**
     * @return mixed|string
     */
    public function getUser(): mixed
    {
        return $this->user;
    }

    /**
     * @return mixed|string
     */
    public function getCharset(): mixed
    {
        return $this->charset;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->pass;
    }
    /**
     * @param mixed|string $host
     */
    public function setHost(mixed $host): void
    {
        $this->host = $host;
    }

    /**
     * @param mixed|string $port
     */
    public function setPort(mixed $port): void
    {
        $this->port = $port;
    }

    /**
     * @param mixed|string $db
     */
    public function setDb(mixed $db): void
    {
        $this->db = $db;
    }

    /**
     * @param mixed|string $user
     */
    public function setUser(mixed $user): void
    {
        $this->user = $user;
    }

    /**
     * @param mixed|string $pass
     */
    public function setPass(mixed $pass): void
    {
        $this->pass = $pass;
    }

    /**
     * @param mixed|string $charset
     */
    public function setCharset(mixed $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

}