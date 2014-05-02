<?php
namespace Vnn\Keyper;

class Keyper
{
    /**
     * Backing data
     */
    protected $array = [];

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
        }
        return $this;
    }
}
