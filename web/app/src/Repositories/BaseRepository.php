<?php

namespace App\Repositories;

use App\Lib\DB\MysqlDatabaseConnection;
use PDO;
use PDOStatement;

class BaseRepository extends MysqlDatabaseConnection
{
    /**
     * @var PDOStatement|bool
     */
    private PDOStatement|bool $stmt;
    /**
     * @var array
     */
    private array $data = array();
    /**
     * @var string|null
     */
    private null|string $where;
    /**
     * @var int|null
     */
    private null|int $count;


    /**
     * @var mixed
     */
    private mixed $fetch;
    /**
     * @var int
     */
    private int $lastId;

    private string $table;

    private string $pk;

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
    public function setTable(string $table): self
    {
        $this->table = strtolower($table);
        return $this;
    }

    /**
     * @param string $pk
     * @return $this
     */
    public function setPrimaryKey(string $pk = 'id'): self
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * Find list of records.
     *
     * @param array $data
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
     * @return false|array
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
     * @return false|array
     */
    public function findById($id): array|false
    {
        return $this->findOne([$this->pk => $id]);
    }

    /**
     * Check whether a record exists.
     *
     * @param $id
     * @return false|array
     */
    public function exists($id): false|array
    {
        if (is_array($id)) {
            return ($this->findOne($id));
        }

        return ($this->findById($id));
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        return $this->fetch;
    }

    /**
     * @return false|int|string
     */
    public function lastSavedId(): false|int|string
    {
        $id = $this->getConnection()?->lastInsertId();
        return ($id) ?: $this->lastId;
    }

    /**
     * Run a SQL statement.
     * @param $sql
     * @return array|false
     */
    public function query($sql)
    {
        $this->stmt = $this->getConnection()?->prepare($sql);
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
    public function save($data): bool
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
     * @throws \PDOException
     */
    public function update($data): bool
    {
        if (!array_key_exists($this->pk, $data)) {
            return false;
        }

        $param = $data;
        $this->where = $this->updateWhere($data);
        $this->stmt = $this->getConnection()?->prepare($this->updateQueryString($data));
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
     * @throws \PDOException
     */
    public function create($data): bool
    {
        $this->data = $data;

        $this->stmt = $this->getConnection()?->prepare($this->insertQueryString());
        $this->param($data);

        $executed = $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
        $this->lastId = $this->getConnection()?->lastInsertId();
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
        $this->stmt = $this->getConnection()?->prepare($sql);

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
    private function param($data = null)
    {
        if (empty($data)) {
            $data = $this->data['conditions'];
        }

        foreach ($data as $k => $v) {
            $tipo = is_numeric($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->stmt->bindValue(":{$this->fixPDOParam($k)}", $v, $tipo);
        }
    }

    /**
     * Prepare selectors.
     *
     * @param $data
     * @return string
     */
    private function fields($data = null)
    {
        if (empty($data) && isset($this->data['fields'])) {
            return implode(',', $this->data['fields']);
        }

        if (!empty($data)) {
            $fields = [];

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
    private function conditions($separator): string
    {
        $param = [];
        foreach ($this->data['conditions'] as $k => $v) {
            $param[] = "{$k} = :{$this->fixPDOParam($k)}";
        }

        return implode($separator, $param);
    }

    /**
     * @return string
     */
    private function where(): string
    {
        return $this->where = (isset($this->data['conditions']))
            ? 'WHERE ' . $this->conditions(' AND ')
            : '';
    }

    /**
     * @return $this
     */
    private function find(): self
    {
        $orderBy = $this->orderBy();
        $limit = $this->limit();
        $offset = $this->offset();

        $sql = "SELECT " . $this->fields() . " FROM {$this->table} " . $this->where() . " " . $orderBy
            . " " . $limit . " " . $offset;

        $this->stmt = $this->getConnection()?->prepare($sql);

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
    private function values(): string
    {
        $values = [];
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
    private function insertQueryString(): string
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
    private function updateWhere(&$data): string
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
    private function updateQueryString($data): string
    {
        $this->data['conditions'] = $data;
        $fields = $this->conditions(',');
        return "UPDATE {$this->table} SET {$fields} {$this->where}";
    }

    /**
     * @return string
     */
    private function orderBy(): string
    {
        return (isset($this->data['order']))
            ? 'ORDER BY ' . $this->data['order']
            : '';
    }

    /**
     * @return string
     */
    private function limit(): string
    {
        return (isset($this->data['limit']))
            ? 'LIMIT :limit'
            : '';
    }

    /**
     * @return string
     */
    private function offset(): string
    {
        return (isset($this->data['offset']))
            ? 'OFFSET :offset'
            : '';
    }

    /**
     * @param string $param
     * @return string
     */
    private function fixPDOParam(string $param): string
    {
        return str_replace('.', '_', $param);
    }

}