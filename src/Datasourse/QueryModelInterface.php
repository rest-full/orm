<?php

namespace Restfull\Datasourse;

use Restfull\ORM\Query;

/**
 * Interface QueryModelInterface
 * @package Restfull\Datasourse
 */
interface QueryModelInterface
{

    /**
     * @param bool|null $bool
     * @return bool
     */
    public function counts(bool $bool = null): bool;

    /**
     * @param string $query
     * @param int $count
     * @param array $options
     * @return Query
     */
    public function setQuery(string $query, int $count = 0, array $options = []): Query;

    /**
     * @param string $name
     * @param int|null $count
     * @return mixed
     */
    public function getQuery(string $name = 'query', int $count = null);

    /**
     * @param int|null $data
     * @return array
     */
    public function getData(int $data = null): array;

    /**
     * @param array $data
     * @return Query
     */
    public function setData(array $data): Query;

    /**
     * @return array
     */
    public function getBindValues(): array;

    /**
     * @param array $bindValue
     * @return Query
     */
    public function setBindValues(array $bindValue): Query;
}
