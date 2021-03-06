<?php

namespace Restfull\ORM;

use Restfull\Core\InstanceClass;
use Restfull\Datasourse\TableTriat;
use Restfull\Error\Exceptions;
use Restfull\Event\Event;
use Restfull\Event\EventDispatcherTrait;
use Restfull\Filesystem\File;
use Restfull\Filesystem\Folder;
use Restfull\ORM\Behavior\Behavior;
use Restfull\ORM\Validate\Validation;

/**
 * Class Table
 * @package Restfull\ORM
 */
class Table
{

    use EventDispatcherTrait;
    use TableTriat;

    /**
     * @var string
     */
    public $place = '';

    /**
     * @var string
     */
    protected $typeQuery = '';

    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @var Behavior
     */
    protected $behaviors;

    /**
     * Table constructor.
     * @throws Exceptions
     */
    public function __construct()
    {
        $this->instance = new InstanceClass();
        $this->validation = $this->instance->resolveClass(
            $this->instance->namespaceClass(
                "%s" . DS_REVERSE . "%s" . DS_REVERSE . "Validate" . DS_REVERSE . "Validation",
                [ROOT_NAMESPACE, MVC[2]['restfull']]
            )
        );
        return $this;
    }

    /**
     *
     */
    public function initialize()
    {
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->typeQuery;
    }

    /**
     * @param array $data
     * @param string $type
     * @return bool
     * @throws Exceptions
     */
    public function keysTables(array $data, string $type = 'create'): bool
    {
        if ($type != 'create') {
            $throws = false;
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    $primaryKey = substr($value, 0, stripos($value, ' '));
                } else {
                    $primaryKey = substr($key, 0, stripos($key, ' '));
                }
                if ($this->tableRegistory->primaryKey != $primaryKey) {
                    $throws = true;
                    break;
                }
            }
            if ($throws) {
                throw new Exceptions("the primary key does not exist in the variable data to register in the table.");
            }
        }
        if (count($table->join) > 0) {
            for ($a = 0; $a < count($table->foreignKey); $a++) {
                $resp[$table->foreignKey[$a]] = 'false';
                if (!in_array($table->foreignKey[$a], $data)) {
                    $resp[$table->foreignKey[$a]] = 'true';
                }
            }
            if (isset($resp)) {
                if (in_array('true', $resp)) {
                    throw new Exceptions(
                        "the foreing key does not exist in the variable data to register in the table."
                    );
                }
            }
        }
        return true;
    }

    /**
     * @param TableRegistry $table
     * @return Table
     */
    public function rules(TableRegistry $table): Table
    {
        $columns = $table->columns[$table->name];
        foreach ($columns as $value) {
            $rules[$value['name']] = '';
            if ($value['required']) {
                $rules[$value['name']] = 'required, ';
            }
            $rules[$value['name']] .= stripos($value['type'], "(") ? 'string' : $value['type'];
        }
        if (method_exists($this, 'validationDefault')) {
            $NewRules = $this->validationDefault();
            foreach ($newRules as $key => $value) {
                if (array_key_exists($key, $rule)) {
                    $rules[$key] = $rule[$key];
                } else {
                    if ($value == "") {
                        unset($rules[$key]);
                    }
                }
            }
        }
        $this->validation->setRules($rules);
        return $this;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exceptions
     */
    public function validate(array $data): bool
    {
        $this->eventProcessVerification('beforeValidator', [$this->validation, $data]);
        $this->validation->setData($data);
        $this->validation->required();
        if ($this->validation->check()) {
            return true;
        }
        $keys = array_keys($data);
        for ($a = 0; $a < count($keys); $a++) {
            $rule = $this->validation->getRules($keys[$a]);
            switch ($rule) {
                case "email":
                    $this->validation->array()->email()->equals();
                    break;
                case "number":
                    $this->validation->numeric();
                    if (stripos(strtolower($keys[$a]), 'numero')) {
                        $this->validation->phone();
                    }
                    break;
                case "price":
                    $this->validation->float();
                    break;
                case "file":
                    $this->validation->url()->file();
                    break;
                default:
                    $this->validation->string()->alphaNumeric()->search();
                    break;
            }
        }
        $folder = new Folder(
            $this->instance->namespaceClass("%s" . DS_REVERSE . "%s", [substr(ROOT_APP, 0, -1), MVC[2]['app']])
        );
        $filesDiretories = $folder->read('Validate');
        foreach ($filesDiretories as $key => $value) {
            for ($a = 0; $a < count($value); $a++) {
                if ($value[$a] != "empty") {
                    $class = $this->instance->namespaceClass(
                        "%s" . DS_REVERSE . "%s" . DS_REVERSE . "Validate" . DS_REVERSE . "%s",
                        [substr(ROOT_APP, 0, -1), MVC[2]['app'], $value[$a]]
                    );
                }
            }
        }
        if ((new File($class))->exists()) {
            $validator = (new InstanceClass())->resolveClass((new InstanceClass())->extension($class));
            $this->validation->error($validator->validated($this->validation->getRules(), $data));
        }
        $this->eventProcessVerification('afterValidator', [$this->validation]);
        return $this->validation->check();
    }

    /**
     * @param string $event
     * @param array|null $data
     * @return mixed|null
     * @throws Exceptions
     */
    public function eventProcessVerification(string $event, array $data = null)
    {
        $event = $this->dispatchEvent(MVC[2]['restfull'] . "." . $event, $data);
        if ($event->getResult() instanceof Response) {
            return null;
        }
        return $event->getResult();
    }

    /**
     * @return mixed
     */
    public function getErrorValidate()
    {
        return $this->validation->error();
    }

    /**
     * @param string $behavior
     * @param array $options
     * @return Table
     * @throws Exceptions
     */
    public function behaviors(string $behavior, array $options = []): Table
    {
        $this->behaviors = $this->instance->resolveClass(
            $this->instance->extension(
                $this->instance->namespaceClass(
                    "%s" . DS_REVERSE . "%s" . DS_REVERSE . "%s" . DS_REVERSE . "%s",
                    [
                        ROOT_NAMESPACE,
                        MVC[2],
                        SUBMVC[2][0],
                        $behavior
                    ]
                )
            )
        );
        if (isset($options['event'])) {
            $this->behaviors->eventProcessVerification($options['event']);
        }
        return $this;
    }

    /**
     * @param string $type
     * @param array $delete
     * @return Table
     */
    public function find(string $type, array $delete): Table
    {
        switch ($type) {
            case "create":
                $this->query->queryAssembly(["DML", "insert"], 0, $delete);
                break;
            case "update":
                $this->query->queryAssembly(["DML", "update"], 0, $delete);
                break;
            case "delete":
                $this->query->queryAssembly(["DML", "delete"], 0, $delete);
                break;
            case "truncate":
                $this->query->queryAssembly(["DDL", "truncate"], 0, $delete);
                break;
            case "query" :
                $this->query->queryAndBindValues();
                break;
            default:
                $count = ($this->queryAssembly == 'several') ? count($query->getData()) : 1;
                for ($a = 0; $a < $count; $a++) {
                    $this->query->queryAssembly(["DML", "select"], $a, $delete);
                }
        }
        $this->query->counts($type == "countRows" ? true : false);
        $this->typeQuery = $type;
        return $this;
    }

    /**
     * @param string $type
     * @return Table
     */
    public function typeQuery(string $type): Table
    {
        if (in_array($type, ['union', 'nested', 'union and nested']) !== false) {
            if (stripos($type, 'and')) {
                $datas = explode('and', $type);
                for ($a = 0; $a < count($datas); $a++) {
                    $data = trim($datas[$a]);
                    $this->query->{$data}();
                }
                return $this;
            }
            if ($type == 'union') {
                $this->query->union();
            }
            if ($type == 'nested') {
                $this->query->nested();
            }
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function countRows(): bool
    {
        return $this->query->counts();
    }

    /**
     * @return int
     * @throws Exceptions
     */
    public function checkArrayQuery(): int
    {
        $query = $this->query->getQuery();
        if (is_array($query) && count($query) > 1) {
            throw new Exceptions('This query must be unique, it cannot contain another query.', 404);
        }
        return 0;
    }

    /**
     * @param int $count
     * @return array
     */
    public function query(int $count): array
    {
        return [$this->query->getQuery('query', $count), $this->query->getBindValues()];
    }

    /**
     * @param bool|null $identify
     * @return mixed
     */
    public function lastId(bool $identify = null)
    {
        if (!is_null($identify)) {
            return $this->query->lastID($identify);
        }
        return $this->query->lastID();
    }

    /**
     * @param Event $event
     * @param Query $query
     * @return null
     */
    public function beforeFind(Event $event, Query $query)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param Table $query
     * @return null
     */
    public function afterFind(Event $event, Table $query)
    {
        return null;
    }

}
