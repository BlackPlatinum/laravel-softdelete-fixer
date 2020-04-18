<?php

namespace BlackPlatinum\Eloquent;

use Closure;

class ModelFinder
{
    /**
     * Array on model class finder closures
     *
     * @var array
     */
    private $finders = [
        'getModelClassFromEnv',
        'getModelClassFormCurrentModel',
        'getModelClassFromAppNamespace',
        'getModelClassFromSearching',
    ];

    public function __construct()
    {
    }

    /**
     * Pass a closure as an other way to find model class
     *
     * @param Closure $closure
     */
    public function addToFinder($closure)
    {
        array_push($this->finders, $closure);
    }

    /**
     *  Try to detect model class using closures in $this->finders array
     *
     * @param string $modelName
     * @param string $model
     * @return bool|string
     */
    function getModel($modelName, $model)
    {
        foreach ($this->finders as $finder) {
            if ($finder instanceof Closure) {
                $result = $finder($modelName, $model);
                if ($result) return $result;
            } elseif (gettype($finder) === 'string') {
                $result = $this->$finder($modelName, $model);
                if ($result) return $result;
            }
        }
        return false;
    }

    /**
     * Last way is searching in declared class to find table's model class
     *
     * @param string $modelName
     * @param string $model
     * @return bool|mixed
     */
    private function getModelClassFromSearching($modelName, $model = null)
    {
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
                if (class_basename($class) === $modelName) {
                    return $class;
                }
                $modelInstance = (object)new $class;
                if ($modelInstance->getTable() === $modelName) {
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
     * @param string $model
     * @return bool|string
     */
    private function getModelClassFromAppNamespace($modelsName, $model = null)
    {
        if (!class_exists(app()->getNamespace() . $modelsName)) {
            return false;
        }
        return app()->getNamespace() . $modelsName;
    }

    /**
     * Try to get model from current query model's namespace
     *
     * @param string $modelName
     * @param string $model
     * @return bool|string
     */
    private function getModelClassFormCurrentModel($modelName, $model = null)
    {
        $thisClass = get_class($model);
        $namespace = str_replace(class_basename($thisClass), '', $thisClass);
        if (!class_exists($namespace . $modelName)) {
            return false;
        }
        return $namespace . $modelName;
    }

    /**
     * Try to get model from environment key
     *
     * @param string $modelName
     * @param string $model
     * @return bool|string
     */
    private function getModelClassFromEnv($modelName, $model = null)
    {
        $namespace = env('MODEL_NAMESPACE', false);
        if (!$namespace) {
            return false;
        }
        if (!class_exists("$namespace\\$modelName")) {
            return false;
        }
        return "$namespace\\$modelName";
    }
}