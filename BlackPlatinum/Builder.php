<?php


namespace App\Component\Extensions;


use Illuminate\Database\Eloquent\SoftDeletes;
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
     * Get Model class by it's name
     *
     * @param string $modelName
     * @return string
     */
    protected function getModelClass(string $modelName): string
    {
        $fromEnv = $this->getModelClassFromEnv($modelName);
        if ($fromEnv) return $fromEnv;

        $fromCurrentModel = $this->getModelClassFormCurrentModel($modelName);
        if ($fromCurrentModel) return $fromCurrentModel;

        $fromNamespace = $this->getModelClassFromAppNamespace($modelName);
        if ($fromNamespace) return $fromNamespace;

        $fromSearch = $this->getModelClassFromSearching($modelName);
        if ($fromSearch) return $fromSearch;

        return false;
    }

    /**
     * Last way is searching in declared class to find table's model class
     *
     * @param string $modelName
     * @return bool|mixed
     */
    protected function getModelClassFromSearching(string $modelName)
    {
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
                if (class_basename($class) === $modelName) {
                    return $class;
                }
            }
        }
        return false;
    }

    /**
     * Try to get model from app namespace
     *
     * @param string $modelsName
     * @return bool|string
     */
    protected function getModelClassFromAppNamespace(string $modelsName)
    {
        if (!class_exists(app()->getNamespace() . $modelsName)) return false;
        return app()->getNamespace() . $modelsName;
    }

    /**
     * Try to get model from current query model's namespace
     *
     * @param string $modelName
     * @return bool|string
     */
    protected function getModelClassFormCurrentModel(string $modelName)
    {
        $thisClass = get_class($this->model);
        $namespace = str_replace(class_basename($thisClass), '', $thisClass);
        if (!class_exists($namespace . $modelName)) return false;
        return $namespace . $modelName;
    }

    /**
     * Try to get model from environment key
     *
     * @param string $modelName
     * @return bool|string
     */
    protected function getModelClassFromEnv(string $modelName)
    {
        $namespace = env('MODEL_NAMESPACE', false);
        if (!$namespace) return false;
        if (!class_exists("$namespace\\$modelName")) return false;
        return "$namespace\\$modelName";
    }

    /**
     * Check given table has soft delete or not
     *
     * @param string $table
     * @return bool
     */
    protected function checkSoftDelete(string $table): bool
    {
        $model = $this->getModelName($table);
        $model = $this->getModelClass($model);
        return in_array(SoftDeletes::class, class_uses_recursive($model));
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
     * @param bool $withTrash
     * @return \Illuminate\Database\Query\Builder|$this|\Illuminate\Database\Eloquent\Builder
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false, $withTrash = false)
    {
        if (!$withTrash) {
            if ($this->checkSoftDelete($table)) {
                return parent::join($table, $first, $operator, $second, $type, $where)
                    ->where("$table.deleted_at", NULL);
            }
        }
        return parent::join($table, $first, $operator, $second, $type, $where);
    }
}