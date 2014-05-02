<?php
namespace Vnn\Keyper;

class KeyperTest extends \PHPUnit_Framework_TestCase
{
    protected $data = [
        'key1' => 'hello'
    ];

    public function test_callable_executes_when_array_key_exists()
    {
        $keyper = new Keyper($this->data);
        $called = false;
        $keyper->when('key1', function($value) use (&$called) {
            $called = $value;
        });
        $this->assertEquals('hello', $called);
    }
}
