<?php

namespace Restfull\Database;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use Restfull\Datasourse\QueryDatabaseInterface;
use Restfull\Error\Exceptions;

/**
 * Class Query
 * @package Restfull\Database
 */
class Query implements QueryDatabaseInterface
{

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Query constructor.
     * @throws Exceptions
     */
    public function __Construct()
    {
        $this->pdo = (new Database())->connecting();
        return $this;
    }

    /**
     * @param Table $data
     * @return bool
     * @throws Exceptions
     */
    public function save(Table $data): bool
    {
        $sql = $data->query();
        $this->query = $this->pdo->prepare($data->query());
        if ($data->count() > 0) {
            foreach ($data->bindValues() as $key => $value) {
                $param = PDO::PARAM_STR;
                if ($value == 'null') {
                    $value = null;
                    $param = PDO::PARAM_NULL;
                }
                $sql = str_replace($key, "'" . trim($value) . "'", $sql);
                $this->query->bindValue($key, $value, $param);
            }
            if ($_SERVER['HTTP_HOST'] == "localhost") {
                if (stripos($sql, 'INFORMATION_SCHEMA.COLUMNS') === false) {
                    $log = new Logger("Erros");
                    $log->pushHandler(new StreamHandler(ROOT . DS . "log" . DS . "sql.log"));
                    $log->log('100', $sql);
                }
            }
            return $this->query->execute() ? true : false;
        }
    }

    /**
     * @param Table $data
     * @return bool
     * @throws Exceptions
     */
    public function update(Table $data): bool
    {
        $sql = $data->query();
        $this->query = $this->pdo->prepare($data->query());
        if ($data->count() > 0) {
            foreach ($data->bindValues() as $key => $value) {
                $param = PDO::PARAM_STR;
                if ($value == 'null') {
                    $value = null;
                    $param = PDO::PARAM_NULL;
                }
                $sql = str_replace($key, "'" . trim($value) . "'", $sql);
                $this->query->bindValue($key, trim($value), $param);
            }
            if ($_SERVER['HTTP_HOST'] == "localhost") {
                if (stripos($sql, 'INFORMATION_SCHEMA.COLUMNS') === false) {
                    $log = new Logger("Erros");
                    $log->pushHandler(new StreamHandler(ROOT . DS . "log" . DS . "sql.log"));
                    $log->log('100', $sql);
                }
            }
            return $this->query->execute() ? true : false;
        }
    }

    /**
     * @param Table $data
     * @return bool
     * @throws Exceptions
     */
    public function delete(Table $data): bool
    {
        $sql = $data->query();
        $this->query = $this->pdo->prepare($data->query());
        if ($data->count() > 0) {
            foreach ($data->bindValues() as $key => $value) {
                $param = PDO::PARAM_STR;
                if ($value == 'null') {
                    $value = null;
                    $param = PDO::PARAM_NULL;
                }
                $sql = str_replace($key, "'" . trim($value) . "'", $sql);
                $this->query->bindValue($key, trim($value), $param);
            }
        }
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            if (stripos($sql, 'INFORMATION_SCHEMA.COLUMNS') === false) {
                $log = new Logger("Erros");
                $log->pushHandler(new StreamHandler(ROOT . DS . "log" . DS . "sql.log"));
                $log->log('500', $sql);
            }
        }
        return $this->query->execute() ? true : false;
    }

    /**
     * @param Table $data
     * @return Query
     * @throws Exceptions
     */
    public function select(Table $data): Query
    {
        $sql = $data->query();
        $this->query = $this->pdo->prepare($data->query());
        if ($data->count() > 0) {
            foreach ($data->bindValues() as $key => $value) {
                $param = PDO::PARAM_STR;
                if ($value == 'null') {
                    $value = null;
                    $param = PDO::PARAM_NULL;
                }
                $sql = str_replace($key, "'" . trim($value) . "'", $sql);
                $this->query->bindValue($key, trim($value), $param);
            }
        }
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            if (stripos($sql, 'INFORMATION_SCHEMA.COLUMNS') === false) {
                $log = new Logger("Erros");
                $log->pushHandler(new StreamHandler(ROOT . DS . "log" . DS . "sql.log"));
                $log->log('100', $sql);
            }
        }
        $this->query->execute();
        return $this;
    }

    /**
     * @param Table $data
     * @return bool
     */
    public function query(Table $data): bool
    {
        return $this->pdo->query($data->query())->execute();
    }

    /**
     * @param Table $data
     * @return bool
     */
    public function truncate(Table $data): bool
    {
        return $this->pdo->prepare($data->query())->execute() ? true : false;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->query->fetchAll();
    }

    /**
     * @return array
     */
    public function first(): array
    {
        $return = $this->query->fetch();
        if (is_bool($return)) {
            return [];
        }
        return $return;
    }

    /**
     * @return int
     */
    public function lastPrimaryKey(): int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query->rowCount();
    }

}
