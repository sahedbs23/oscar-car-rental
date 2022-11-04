<?php

namespace App\Repositories;

use App\Lib\DB\MysqlDatabaseConnection;
use \PDO;

class BaseRepository extends MysqlDatabaseConnection
{
    protected $stmt;
    protected array $data = array();
    protected $sql;
    protected $where;
    protected $fields;
    protected int $count;
    protected $fetch;
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
     * @param string $table
     * @return $this
     */
    protected function setTable(string $table)
    {
        $this->table = strtolower($table);
        return $this;
    }

    protected function setPrimaryKey(string $pk = 'id')
    {
        $this->pk = $pk;
    }

    protected function param($data = null)
    {
        if (empty($data)) {
            $data = $this->data['conditions'];
        }

        foreach ($data as $k => $v) {
            $tipo = (is_int($v)) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->stmt->bindValue(":{$k}", $v, $tipo);
        }
    }

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

    protected function conditions($separator)
    {
        $param = [];
        foreach ($this->data['conditions'] as $k => $v) {
            $param[] = "{$k} = :{$k}";
        }

        return implode($separator, $param);
    }

    protected function where()
    {
        return $this->where = (isset($this->data['conditions']))
            ? 'WHERE ' . $this->conditions(' AND ')
            : '';
    }

    protected function find()
    {
        $sql = "SELECT " . $this->fields() . " FROM {$this->table} " . $this->where();

        $this->stmt = $this->connection->prepare($sql);

        if (!empty($this->where)) {
            $this->param();
        }

        $this->stmt->execute();
        return $this;
    }

    protected function values()
    {
        foreach ($this->data as $k => $v) {
            $values[] = ":{$k}";
        }

        return implode(',', $values);
    }

    protected function insertQueryString()
    {
        $fields = $this->fields($this->data);
        $values = $this->values();

        return "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
    }

    protected function updateWhere($data)
    {
        $this->data['conditions'] = [$this->pk => $data[$this->pk]];
        $where = 'WHERE ' . $this->conditions('');
        unset($data[$this->pk]);

        return $where;
    }

    protected function updateQueryString($data)
    {
        $this->data['conditions'] = $data;
        $fields = $this->conditions(',');

        return "UPDATE {$this->table} SET {$fields} {$this->where}";
    }

    public function findAll($data = null)
    {
        $this->data = $data;
        return $this->find()
            ->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($data)
    {
        $this->data['conditions'] = $data;
        return $this->fetch = $this->find()
            ->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        return $this->findOne([$this->pk => $id]);
    }

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
        return ($id) ? $id : $this->lastId;
    }

    public function query($sql)
    {
        $this->stmt = $this->connection->prepare($sql);
        $this->stmt->execute();
        $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->count = count($result);

        return $result;
    }

    public function save($data)
    {
        if (array_key_exists($this->pk, $data)) {
            $this->count = $this->findOne([$this->pk => $data[$this->pk]]);
            $this->lastId = $data[$this->pk];
        }

        if (!empty($this->count)) {
            return $this->update($data);
        }

        return $this->create($data);
    }

    public function update($data)
    {
        if (!array_key_exists($this->pk, $data)) {
            return false;
        }

        $param = $data;
        $this->where = $this->updateWhere($data);
        $this->stmt = $this->connection->prepare($this->updateQueryString($data));
        $this->param($param);
        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

    public function create($data)
    {
        $this->data = $data;

        $this->stmt = $this->connection->prepare($this->insertQueryString());
        $this->param($data);

        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

    public function delete($data)
    {
        $this->data['conditions'] = $data;

        $sql = "DELETE FROM {$this->table} " . $this->where();
        $this->stmt = $this->connection->prepare($sql);

        if (!empty($this->where)) {
            $this->param();
        }

        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

}