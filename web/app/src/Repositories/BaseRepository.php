<?php

namespace App\Repositories;

use App\Lib\DB\MysqlDatabaseConnection;
use \PDO;

class BaseRepository extends MysqlDatabaseConnection
{
    /**
     * @var \PDOStatement|bool
     */
    protected \PDOStatement|bool $stmt;
    /**
     * @var array
     */
    protected array $data = array();
    protected $sql;
    /**
     * @var string|null
     */
    protected null|string $where;
    /**
     * @var string|null
     */
    protected null|string $fields;
    /**
     * @var int|null
     */
    protected null|int $count;


    /**
     * @var mixed
     */
    protected mixed $fetch;
    /**
     * @var int
     */
    protected int $lastId;

    protected string $table;

    protected string $pk;

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey();
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = strtolower($table);
        return $this;
    }

    /**
     * @param string $pk
     * @return $this
     */
    public function setPrimaryKey(string $pk = 'id')
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * Find list of records.
     *
     * @param $data
     * @return array|false
     */
    public function findAll(array $data)
    {
        $this->data = $data;
        return $this->find()
            ->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a single record.
     *
     * @param $data
     * @return mixed
     */
    public function findOne($data)
    {
        $this->data['conditions'] = $data;
        return $this->fetch = $this->find()
            ->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find by PK.
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->findOne([$this->pk => $id]);
    }

    /**
     * Check whether a record exists.
     *
     * @param $id
     * @return mixed
     */
    public function exists($id)
    {
        if (is_array($id)) {
            return ($this->findOne($id));
        }

        return ($this->findById($id));
    }

    public function fetch()
    {
        return $this->fetch;
    }

    public function lastSavedId()
    {
        $id = $this->connection->lastInsertId();
        return ($id) ?: $this->lastId;
    }

    /**
     * Run a SQL statement.
     * @param $sql
     * @return array|false
     */
    public function query($sql)
    {
        $this->stmt = $this->connection->prepare($sql);
        $this->stmt->execute();
        $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->count = count($result);

        return $result;
    }

    /**
     * Create or Update records.
     *
     * @param $data
     * @return bool
     */
    public function save($data) :bool
    {
        if (array_key_exists($this->pk, $data)) {
            $exists = $this->findOne([$this->pk => $data[$this->pk]]);
            $this->count = !empty($exists) ? $exists[$this->pk] : null;
            $this->lastId = $data[$this->pk];
        }

        if (!empty($this->count)) {
            return $this->update($data);
        }

        return $this->create($data);
    }

    /**
     * Update record.
     *
     * @param $data
     * @return bool
     */
    public function update($data):bool
    {
        if (!array_key_exists($this->pk, $data)) {
            return false;
        }

        $param = $data;
        $this->where = $this->updateWhere($data);
        $this->stmt = $this->connection->prepare($this->updateQueryString($data));
        $this->param($param);
        $response = $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
        return $response;
    }

    /**
     * Insert a records.
     *
     * @param $data
     * @return bool
     */
    public function create($data) :bool
    {
        $this->data = $data;

        $this->stmt = $this->connection->prepare($this->insertQueryString());
        $this->param($data);

        $executed = $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
        $this->lastId = $this->connection->lastInsertId();
        return $executed;
    }

    /**
     * @param $data
     * @return bool
     */
    public function delete($data)
    {
        $this->data['conditions'] = $data;

        $sql = "DELETE FROM {$this->table} " . $this->where();
        $this->stmt = $this->connection->prepare($sql);

        if (!empty($this->where)) {
            $this->param();
        }

        $res = $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
        return $res;
    }

    /**
     * bind value to pdo statement.
     *
     * @param $data
     * @return void
     */
    protected function param($data = null)
    {
        if (empty($data)) {
            $data = $this->data['conditions'];
        }

        foreach ($data as $k => $v) {
            $tipo = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->stmt->bindValue(":{$k}", $v, $tipo);
        }
    }

    /**
     * Prepare selectors.
     *
     * @param $data
     * @return string
     */
    protected function fields($data = null)
    {
        if (empty($data) && isset($this->data['fields'])) {
            return implode(',', $this->data['fields']);
        }

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $fields[] = $k;
            }
            return implode(',', $fields);
        }

        return '*';
    }

    /**
     * @param $separator
     * @return string
     */
    protected function conditions($separator)
    {
        $param = [];
        foreach ($this->data['conditions'] as $k => $v) {
            $param[] = "{$k} = :{$k}";
        }

        return implode($separator, $param);
    }

    /**
     * @return string
     */
    protected function where()
    {
        return $this->where = (isset($this->data['conditions']))
            ? 'WHERE ' . $this->conditions(' AND ')
            : '';
    }

    /**
     * @return $this
     */
    protected function find()
    {
        $orderBy= $this->orderBy();
        $limit = $this->limit();
        $offset = $this->offset();

        $sql = "SELECT " . $this->fields() . " FROM {$this->table} " . $this->where() . " ". $orderBy
            . " ". $limit. " ". $offset;

        $this->stmt = $this->connection->prepare($sql);

        if (!empty($this->where)) {
            $this->param();
        }

        if (!empty($limit)) {
            $this->stmt->bindValue(':limit', (int)$this->data['limit'], PDO::PARAM_INT);
        }

        if (!empty($limit) && !empty($offset)) {
            $this->stmt->bindValue(':offset', (int)$this->data['offset'], PDO::PARAM_INT);
        }

        $this->stmt->execute();
        return $this;
    }



    /**
     * @return string
     */
    protected function values()
    {
        foreach ($this->data as $k => $v) {
            $values[] = ":{$k}";
        }

        return implode(',', $values);
    }

    /**
     * Prepare insert statement
     *
     * @return string
     */
    protected function insertQueryString():string
    {
        $fields = $this->fields($this->data);
        $values = $this->values();

        return "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
    }

    /**
     * Prepare where condistions.
     * @param $data
     * @return string
     */
    protected function updateWhere(&$data):string
    {
        $this->data['conditions'] = [$this->pk => $data[$this->pk]];
        $where = 'WHERE ' . $this->conditions('');
        unset($data[$this->pk]);
        return $where;
    }

    /**
     * Prepare update statement
     * @param $data
     * @return string
     */
    protected function updateQueryString($data):string
    {
        $this->data['conditions'] = $data;
        $fields = $this->conditions(',');
        return "UPDATE {$this->table} SET {$fields} {$this->where}";
    }

    /**
     * @return string
     */
    protected function orderBy() {
        return  (isset($this->data['order']))
            ? 'ORDER BY ' . $this->data['order']
            : '';
    }

    protected function limit() {
        return  (isset($this->data['limit']))
            ? 'LIMIT :limit'
            : '';
    }
    /**
     * @return string
     */
    protected function offset() {
        return  (isset($this->data['offset']))
            ? 'OFFSET :offset'
            : '';
    }

}