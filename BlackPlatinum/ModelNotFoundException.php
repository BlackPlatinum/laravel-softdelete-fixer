<?php


namespace BlackPlatinum;

use Exception;
use Throwable;

class ModelNotFoundException extends Exception
{
    private $table;

    public function __construct($table = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Can't find given table Model class: {$table}", $code, $previous);
        $this->table = $table;
    }

    public function __toString()
    {
        return "Model notfound, table name: {$this->table}";
    }
}