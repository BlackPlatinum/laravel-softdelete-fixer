<?php

namespace BlackPlatinum;

use Illuminate\Support\Str;

class Builder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * Get Model name by table name
     *
     * @param string $table
     * @return string
     */
    protected function getModelName(string $table): string
    {
        return Str::studly(Str::singular($table));
    }

    /**
     * Add a join clause to the query.
     *
     * @param string $table
     * @param \Closure|string $first
     * @param string|null $operator
     * @param string|null $second
     * @param string $type
     * @param bool $where
     * @return \Illuminate\Database\Query\Builder|$this|\Illuminate\Database\Eloquent\Builder
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        return parent::join($table, $first, $operator, $second, $type, $where)
            ->where("$table.deleted_at", NULL);
    }
}