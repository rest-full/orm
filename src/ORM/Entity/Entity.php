<?php

namespace Restfull\ORM\Entity;

use Restfull\ORM\TableRegistry;

/**
 * Class Entity
 * @package Restfull\ORM\Entity
 */
class Entity
{

    /**
     * @var TableRegistry
     */
    public $repository;

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var array
     */
    public $option = [];

    /**
     * @param string $type
     * @param array $options
     * @return Entity
     */
    public function config(string $type, array $options = []): Entity
    {
        $this->type = $type;
        $this->option = $options;
        return $this;
    }

    /**
     * @param TableRegistry $table
     * @return Entity
     */
    public function entity(TableRegistry $table): Entity
    {
        if ($this->type == "query") {
            foreach ($this->option['result'] as $key => $value) {
                $this->$key = $value;
            }
            unset($this->option, $this->repository, $this->type);
            return $this;
        } elseif ($this->type != 'open') {
            if (isset($this->option['fields'])) {
                if ($this->option['fields'][0] == 'count') {
                    $this->count = $this->option['result']['count'];
                    if (count($this->option['result']) == 1) {
                        return $this;
                    }
                    unset($this->option['result']['count'], $this->option['fields'][0]);
                }
                $columns = $this->selectColumns($table);
            }
        }
        if (!isset($columns)) {
            $columns = ['columns' => $this->option['fields'], 'entity' => null];
        }
        $this->createEntity($table, $columns['columns'], $columns['entity']);
        unset($this->option, $this->repository, $this->type);
        return $this;
    }

    /**
     * @param TableRegistry $table
     * @return array
     */
    private function selectColumns(TableRegistry $table): array
    {
        $entity = $columns = ['main' => [], 'foreing' => []];
        $keys = array_keys($table->columns);
        for ($a = 0; $a < count($keys); $a++) {
            $column = $table->columns[$keys[$a]];
            for ($b = 0; $b < count($column); $b++) {
                if (isset($this->option['fields'])) {
                    if (in_array($column[$b]['name'], $this->option['fields']) !== false) {
                        $columns['main'][] = $column[$b]['name'];
                    }
                } else {
                    $columns['main'][] = $column[$b]['name'];
                }
                if (count($table->entity) > 0) {
                    if (in_array($column[$b]['type'], $table->entity[$keys[$a]]) !== false) {
                        $entity['main'][count($columns['main']) - 1] = $table->entity[$keys[$a]][$column[$b]['name']];
                    }
                }
            }
        }
        if (isset($table->join)) {
            $keys = array_keys($table->join);
            for ($a = 0; $a < count($keys); $a++) {
                $column = $table->join[$keys[$a]];
                for ($b = 0; $b < count($column); $b++) {
                    if (isset($this->option['fields'])) {
                        if (!in_array($column[$b]['name'], $columns['main']) !== false && in_array(
                                $column[$b]['name'],
                                $this->option['fields']
                            ) !== false) {
                            $columns['foreing'][] = $column[$b]['name'];
                        }
                    } else {
                        if (!in_array($column[$b]['name'], $columns) !== false) {
                            $columns['foreing'][] = $column[$b]['name'];
                        }
                    }
                    if (count($table->entity) > 0) {
                        if (in_array($column[$b]['type'], $table->entity[$keys[$a]]) !== false) {
                            $entity['foreing'][count(
                                $columns['main']
                            ) - 1] = $table->entity[$keys[$a]][$column[$b]['name']];
                        }
                    }
                }
            }
        }
        $keys = ['columns', 'entity'];
        for ($a = 0; $a < count($keys); $a++) {
            if (isset(${$keys[$a]})) {
                $datas[$keys[$a]] = ${$keys[$a]}['main'];
                if (count(${$keys[$a]}['foreing']) > 0) {
                    foreach (${$keys[$a]}['foreing'] as $key) {
                        if (in_array($key, $datas[$keys[$a]]) === false) {
                            $datas[$keys[$a]][] = $key;
                        }
                    }
                    unset(${$keys[$a]}['foreing']);
                }
            }
        }
        if (!isset($datas['entity'])) {
            $datas['entity'] = null;
        }
        return $datas;
    }

    /**
     * @param TableRegistry $table
     * @param array $columns
     * @param array|null $entity
     * @return Entity
     */
    private function createEntity(TableRegistry $table, array $columns, array $entity = null): Entity
    {
        $datas = '';
        foreach ($columns as $key) {
            if (in_array($key, array_keys($this->option['result']))) {
                if (isset($entity[array_search($key, $columns)])) {
                    $this->option['result'][$key] = $this->{$entity[array_search($key, $columns)]}(
                        $this->option['result'][$key]
                    );
                }
                $this->$key = isset($this->option['result'][$key]) ? $this->option['result'][$key] : '';
            }
        }
        return $this;
    }

    /**
     * @param string $msg
     * @return string
     */
    public function utf8Fix(string $msg): string
    {
        $accents = [
            "á",
            "à",
            "â",
            "ã",
            "ä",
            "é",
            "è",
            "ê",
            "ë",
            "í",
            "ì",
            "î",
            "ï",
            "ó",
            "ò",
            "ô",
            "õ",
            "ö",
            "ú",
            "ù",
            "û",
            "ü",
            "ç",
            "Á",
            "À",
            "Â",
            "Ã",
            "Ä",
            "É",
            "È",
            "Ê",
            "Ë",
            "Í",
            "Ì",
            "Î",
            "Ï",
            "Ó",
            "Ò",
            "Ô",
            "Õ",
            "Ö",
            "Ú",
            "Ù",
            "Û",
            "Ü",
            "Ç",
            "-",
            "ª",
            "º"
        ];
        $utf8 = [
            "Ã¡",
            "Ã ",
            "Ã¢",
            "Ã£",
            "Ã¤",
            "Ã©",
            "Ã¨",
            "Ãª",
            "Ã«",
            "Ã­",
            "Ã¬",
            "Ã®",
            "Ã¯",
            "Ã³",
            "Ã²",
            "Ã´",
            "Ãµ",
            "Ã¶",
            "Ãº",
            "Ã¹",
            "Ã»",
            "Ã¼",
            "Ã§",
            "Ã",
            "Ã€",
            "Ã‚",
            "Ãƒ",
            "Ã„",
            "Ã‰",
            "Ãˆ",
            "ÃŠ",
            "Ã‹",
            "Ã",
            "ÃŒ",
            "ÃŽ",
            "Ã",
            "Ã“",
            "Ã’",
            "Ã”",
            "Ã•",
            "Ã–",
            "Ãš",
            "Ã™",
            "Ã›",
            "Ãœ",
            "Ã‡",
            "â€“",
            "Âª",
            "Âº"
        ];
        for ($a = 0; $a < count($utf8); $a++) {
            if (stripos($msg, $utf8[$a])) {
                $msg = str_replace($utf8[$a], $accents[$a], $msg);
            }
        }
        return $msg;
    }

}
