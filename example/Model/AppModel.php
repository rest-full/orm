<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this Template file, choose Tools | Templates
 * and open the Template in the editor.
 */

namespace App\Model;

use Restfull\Core\InstanceClass;
use Restfull\Error\Exceptions;
use Restfull\ORM\Table;
use Restfull\ORM\TableRegistry;

/**
 * Description of MainModel
 * @package App\Model
 */
class AppModel extends Table
{

    /**
     * @param array $tables
     * @throws Exceptions
     */
    public function tableRegistry(array $tables): TableRegistry
    {
        $instance = new InstanceClass();
        $namespace = stripos(RESTFULL, 'vendor') !== false ? str_replace(
            substr(RESTFULL, stripos(RESTFULL, "vendor") + strlen('vendor' . DS), -1),
            "Restfull",
            substr(RESTFULL, 0, -1)
        ) : RESTFULL;
        $table = $instance->resolveClass(
            $instance->namespaceClass(
                "%s\\%s\\%sRegistry",
                [$namespace, MVC[2]['restfull'], SUBMVC[2][2]]
            )
        );
        $tables['main'] = $table->entityColumns($table->registory($tables['main']));
        if (isset($tables['join'])) {
            for ($a = 0; $a < count($tables['join']); $a++) {
                $tables['main']->entityColumns($tables['main']->registory($tables['join'][$a], 'join'));
            }
        }
        return $tables['main'];
    }
}
