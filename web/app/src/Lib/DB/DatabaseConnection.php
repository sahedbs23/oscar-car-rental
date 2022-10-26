<?php

class DatabaseConnection
{
    public ?PDO $connection;

    private string $host;

    private string $db;

    private string $user;

    private string $pass;

    private string $charset;

    private array $options;

    public function __construct($errorMode = PDO::ERRMODE_EXCEPTION, $fetchMode =  PDO::FETCH_ASSOC, $emulate = false)
    {
        $this->host = '127.0.0.1'; // Read from config
        $this->db   = 'test'; // Read from config
        $this->user = 'root'; // Read from config
        $this->pass = 'root';// Read from config
        $this->charset = 'utf8mb4';// Read from config

        $this->options = [
            PDO::ATTR_ERRMODE            => $errorMode,
            PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode,
            PDO::ATTR_EMULATE_PREPARES   => $emulate
        ];

        $this->connection = $this->connect();
    }

    public function connect(): ?PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

        try {
            return new PDO($dsn, $this->user, $this->pass, $this->options);
        } catch (\PDOException $e) {
//            Log::critical($e->getMessage());
            return null;
        }
    }







}