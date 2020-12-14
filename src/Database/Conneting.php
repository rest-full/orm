<?php

namespace Restfull\Database;

use PDO;
use PDOException;
use Restfull\Error\Exceptions;

/**
 * Class Database
 * @package Restfull\Database
 */
class Conneting extends Drive
{

    /**
     * @var array
     */
    private static $bancos = [];

    /**
     * @var array
     */
    private static $conexoes = [];

    /**
     * @var string
     */
    private static $conexaoUsar;

    /**
     * @var array
     */
    private static $timestamp = [];

    /**
     * @var PDO
     */
    private static $PDO;

    /**
     * @return PDO
     * @throws Exceptions
     */
    public static function connection(): PDO
    {
        $banco = self::$bancos[self::$conexaoUsar];
        $dbtype = self::getDrive();
        self::conexaoPDO();
        if (empty(self::$PDO)) {
            try {
                if (in_array(self::$conexaoUsar, ['default', 'test']) === false) {
                    $dbtype = str_replace(self::$bancos['default']['host'], $banco['host'], $dbtype);
                    $dbtype = str_replace(self::$bancos['default']['dbname'], $banco['dbname'], $dbtype);
                }
                if (substr($dbtype, 0, stripos($dbtype, ":")) != "pgsql") {
                    self::$PDO = new PDO($dbtype, $banco['username'], $banco['password']);
                } else {
                    self::$PDO = new PDO($dbtype);
                }
            } catch (PDOException $e) {
                throw new Exceptions($e, 404);
            }
        }
        self::$timestamp[self::$conexaoUsar] = strtotime(date('Y-m-d H:i:s') . ' +25 minutes');
        self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        self::$PDO->setAttribute(
            PDO::MYSQL_ATTR_INIT_COMMAND,
            "SET NAME " . $banco['charset'] . " COLLATE " . $banco['collation']
        );
        self::$conexoes[self::$conexaoUsar] = self::$PDO;
        return self::$PDO;
    }

    /**
     *
     */
    public static function conexaoPDO(): void
    {
        self::$PDO = isset(self::$conexoes[self::$conexaoUsar]) ? self::$conexoes[self::$conexaoUsar] : '';
        if (!empty(self::$PDO)) {
            if (!isset(self::$timestamp[self::$conexaoUsar]) || (strtotime(
                        date('Y-m-d H:i:s')
                    ) > self::$timestamp[self::$conexaoUsar])) {
                self::$PDO = '';
            }
        }
        return;
    }

    /**
     * @return bool
     */
    public static function existDatabase(): PDO
    {
        $banco = self::$bancos[self::$conexaoUsar];
        if (self::$conexaoUsar != 'default') {
            $PDO = self::$conexoes[self::$conexaoUsar];
        }
        if (empty($PDO)) {
            $PDO = new PDO(self::getDrive(), $banco['username'], $banco['password']);
            self::$conexoes[self::$conexaoUsar] = $PDO;
        }
        return $PDO;
    }

    /**
     * @param array $banco
     */
    public static function setBanco(array $banco): void
    {
        self::$bancos[self::$conexaoUsar] = $banco;
        if (!isset(self::$conexoes[self::$conexaoUsar])) {
            self::$conexoes[self::$conexaoUsar] = '';
        }
        return;
    }

    /**
     * @param string $dbname
     */
    public static function connexion(string $dbname): void
    {
        self::$conexaoUsar = $dbname;
        return;
    }

}
