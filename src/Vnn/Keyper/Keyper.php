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

    public function when($key, callable $fn)
    {
        if (isset($this->array[$key])) {
            $fn($this->array[$key]);
            return $this;
        }

        $value = static::getValueFromArray($key);

        if ($value) {
            $value = (is_array($value)) ? $value : [$value];
            call_user_func_array($fn, $value);
        }

        return $this;
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
            if (isset($data[$k])) {
                $value = $data[$k];
            }
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
