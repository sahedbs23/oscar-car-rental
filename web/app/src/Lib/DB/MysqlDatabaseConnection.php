<?php

namespace App\Lib\DB;

use App\Abstracts\AbstractDatabaseConnection;
use App\Helpers\ReadConfig;
use PDO;

class MysqlDatabaseConnection extends AbstractDatabaseConnection
{
    /**
     * @var array
     */
    public array $configArr;

    /**
     * @inheritDoc
     */
    public function __construct(
        array $config = [],
        $errorMode = PDO::ERRMODE_EXCEPTION,
        $fetchMode = PDO::FETCH_ASSOC,
        $emulate = false
    ) {
        $this->configArr = $config;
        parent::__construct($errorMode, $fetchMode, $emulate);
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        if (!empty($this->configArr)) {
            return $this->configArr;
        }
        //TODO:: Replace with static mysql connection. For now Keep it simple.
        $connection = ReadConfig::config('database', 'default_connection');
        return ReadConfig::config('database', 'connection')[$connection];
    }

    /**
     * @inheritDoc
     */
    protected function connect(
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
}