<?php
namespace Vnn\Keyper;

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
     * Execute callable when an array key exists
     *
     * @param $key
     * @param callable $fn
     * @return $this
     */
    public function when($key, callable $fn)
    {
        $args = $this->getArgs($key);
        if ($args) {
            call_user_func_array($fn, $args);
        }
    }

    /**
     * @param $key
     * @param array $values
     * @return array
     */
    protected function getArgs($key, &$values = []) {

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
     * @param $key
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

    public static function create(array $array)
    {
        $keyper = new static($array);
        return $keyper;
    }
}
