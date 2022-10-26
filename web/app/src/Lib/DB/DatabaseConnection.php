<?php
namespace App\Oscar\Lib\DB;

use PDO;

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

    public function __construct($errorMode = PDO::ERRMODE_EXCEPTION, $fetchMode =  PDO::FETCH_ASSOC, $emulate = false)
    {
        $config = include __DIR__ . '/../../../config/database.php';

        $this->db   = $config['database'];
        $this->host = $config['host'];
        $this->port   = $config['port'];
        $this->user =$config['user'];
        $this->pass = $config['pass'];
        $this->charset = $config['charset'];

        $this->options = [
            PDO::ATTR_ERRMODE            => $errorMode,
            PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode,
            PDO::ATTR_EMULATE_PREPARES   => $emulate
        ];

        $this->connection = $this->connect();
    }

    private function connect(): ?PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset;port=$this->port";

        try {
            return new PDO($dsn, $this->user, $this->pass, $this->options);
        } catch (\PDOException $e) {
//            Log::critical($e->getMessage());
            return null;
        }
    }







}