<?php

namespace Vnn\Keyper;

/**
 * Class Keyper
 * @package Vnn\Keyper
 */
class Keyper
{
    /**
     * Backing data
     */
    protected $array = [];

    /**
     * @var string
     */
    private static $dotNotation = '/[.]/';

    /**
     * Constructor
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Create a new Keyper instance.
     *
     * @param array $array
     * @return static
     */
    public static function create(array $array)
    {
        $keyper = new static($array);
        return $keyper;
    }

    /**
     * Execute callables when an array key exists. Takes one or more callable functions and executes them right to
     * left passing the result of each function to the function on its left.
     *
     * @param mixed $key
     * @param callable $fn
     * @return $this
     */
    public function when($key, callable $fn)
    {
        return $this->execute($this->getArgs($key), func_get_args());
    }

    /**
     * Execute callables when an all of the provided array keys exists. Takes one or more callable functions and
     * executes them right to left passing the result of each function to the function on its left.
     *
     * @param array $key
     * @param callable $fn
     * @return $this
     */
    public function whenAll(array $key, callable $fn)
    {
        $args = array_filter($this->getArgs($key));
        if (count($key) == count($args)) {
            return $this->execute($args, func_get_args());
        }

        return $this;
    }

    /**
     * @param mixed $key
     * @param array $values
     * @return array
     */
    protected function getArgs($key, &$values = [])
    {

        if (is_array($key)) {
            foreach ($key as $k) {
                $this->getArgs($k, $values);
            }
            return $values;
        }

        if (isset($this->array[$key])) {
            $values[] = $this->array[$key];
        }

        if (preg_match(static::$dotNotation, $key)) {
            $values[] = $this->getValueFromArray($key);
        }

        return $values;
    }

    /**
     * Check if at least one non null value exists in args.
     *
     * @param array $args
     * @return bool
     */
    protected function canExecute(array $args)
    {
        return count(array_filter($args, function ($arg) {
            return !is_null($arg);
        })) > 0;
    }

    /**
     * @param mixed $key
     * @return null
     */
    protected function getValueFromArray($key)
    {
        $parts = explode('.', $key);
        $value = null;
        $data = $this->array;
        foreach ($parts as $k) {
            $value = array_key_exists($k, $data) ? $data[$k] : null;
            if (!is_array($value)) {
                break;
            }
            $data = $value;
        }
        return $value;
    }

    /**
     * @param $args
     * @param $funcArgs
     * @return $this
     */
    protected function execute($args, $funcArgs)
    {
        if ($this->canExecute($args)) {
            $funcs = array_slice($funcArgs, 1);
            $result = $args;
            while ($fn = array_pop($funcs)) {
                $result = [call_user_func_array($fn, $result)];
            }
        }

        return $this;
    }
}
