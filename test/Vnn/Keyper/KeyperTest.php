<?php
namespace Vnn\Keyper;

class KeyperTest extends \PHPUnit_Framework_TestCase
{
    protected $data = [
        'key1' => 'hello',
        'nested' => [
            'one' => 1,
            'two' => 2,
            'three' => [
                'four' => 5
            ]
        ]
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

    public function test_callable_does_not_execute_if_key_missing()
    {
        $keyper = Keyper::create($this->data);
        $called = false;
        $keyper->when('key2', function($value) use (&$called) {
            $called = true;
        });
        $this->assertFalse($called);
    }

    public function test_callable_executes_on_nested_key()
    {
        $keyper = Keyper::create($this->data);
        $called = false;
        $keyper->when('nested.one', function($value) use (&$called) {
            $called = $value;
        });
        $this->assertEquals(1, $called);
    }

    public function test_callable_executes_on_nested_key_regardless_of_depth()
    {
        $keyper = Keyper::create($this->data);
        $called = false;
        $keyper->when('nested.three.four', function($value) use (&$called) {
            $called = $value;
        });
        $this->assertEquals(5, $called);
    }
}
