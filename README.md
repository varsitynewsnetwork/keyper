Keyper
======

> Do things when an array has a key

Usage
-----

```php
$data = [
    'key1' => 'hello',
    'nested' => [
        'one' => 1,
        'two' => 2,
        'three' => [
            'four' => 5
        ]
    ]
];

$keyper = Keyper::create($data);

//do something with a single value
$keyper->when('key1', function($value) {
    //$value == 'hello'
    print $value;
});

//drill down a nested array
$keyper->when('nested.three.four', function($value) {
    //$value == 5
    print $value;
});

//do something with multiple keys
$keyper->when(['nested.one', 'nested.two', function($one, $two) {
    //$one = 1
    //$two = 2;
    print $one + $two;
});
```

Running tests
-------------

```code
composer install

vendor/bin/phpunit
```