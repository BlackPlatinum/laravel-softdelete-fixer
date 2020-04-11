<?php

namespace BlackPlatinum\Exceptions;

use RuntimeException;
use Throwable;

class ModelNotFoundException extends RuntimeException
{
    /**
     * @var string The model's table.
     */
    private $table;

    public function __construct($table = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Can't find table {$table} for Model", $code, $previous);
        $this->table = $table;
    }

    public function __toString()
    {
        return "Model not found, table name: {$this->table}";
    }
}
