<?php

namespace Restfull\Database;

use PDO;
use Restfull\Error\Exceptions;

/**
 * Class Database
 * @package Restfull\Database
 */
class Database
{

    /**
     * @var array
     */
    private $details = [];

    /**
     * @var string
     */
    private $place = '';

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $conneting;

    /**
     * @var array
     */
    private $validDetailsIsNotEmpty = ['host' => false, 'password' => false, 'username' => false, 'dbname' => false];

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->conneting = Conneting::class;
        return $this;
    }

    /**
     * @return Database
     */
    public function instance(): Database
    {
        $db = $this->details[$this->place];
        Conneting::setBanco($db);
        Conneting::setDrive($db);
        return $this;
    }

    /**
     * @param array $details
     * @return Database
     */
    public function details(array $details): Database
    {
        $this->details[$this->place] = $details;
        return $this;
    }

    /**
     * @param string $place
     * @return Database
     */
    public function place(string $place): Database
    {
        $this->place = $place;
        return $this;
    }

    /**
     * @return bool
     */
    public function validConnetion(): bool
    {
        Conneting::connexion($this->place);
        if (Conneting::existDatabase() instanceof PDO) {
            return true;
        }
        return false;
    }

    /**
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return Database
     * @throws Exceptions
     */
    public function conneting(): Database
    {
        $this->pdo = Conneting::connection();
        return $this;
    }

    /**
     * @return bool
     */
    public function validNotEmpty(): bool
    {
        foreach (array_keys($this->validDetailsIsNotEmpty) as $key) {
            if ($this->details[$this->place][$key] == '') {
                $this->validDetailsIsNotEmpty[$key] = true;
            }
        }
        $count = 4;
        foreach ($this->validDetailsIsNotEmpty as $value) {
            if ($value) {
                $count--;
            }
        }
        if ($count < 4) {
            return true;
        }
        return false;
    }
}