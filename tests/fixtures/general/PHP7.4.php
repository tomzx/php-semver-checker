<?php

// PHP 7.4 Features

// Typed properties
class User
{
    public int $id;
    public string $name;
    public ?string $email = null;
    private array $roles = [];
    protected float $balance = 0.0;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

// Arrow functions
$numbers = [1, 2, 3, 4, 5];

$squared = array_map(fn($n) => $n * $n, $numbers);
$doubled = array_map(fn($n) => $n * 2, $numbers);

$multiply = fn($a, $b) => $a * $b;
$result = $multiply(5, 10);

// Null coalescing assignment operator
class Config
{
    private array $data = [];

    public function get(string $key): mixed
    {
        $this->data[$key] ??= $this->loadDefault($key);
        return $this->data[$key];
    }

    private function loadDefault(string $key): string
    {
        return 'default';
    }
}

// Spread operator in array expression
function spreadInArrays(): array
{
    $array1 = [1, 2, 3];
    $array2 = [4, 5, 6];

    $merged = [...$array1, ...$array2];

    return $merged;
}

// Numeric literal separator
function numericLiterals(): void
{
    $million = 1_000_000;
    $binary = 0b1010_1011;
    $hex = 0xFF_FF_FF;
    $float = 1_234.567_890;
}

// Weak references
class WeakReferenceExample
{
    private object $object;

    public function __construct(object $obj)
    {
        $this->object = $obj;
    }

    public function createWeakRef(): \WeakReference
    {
        return \WeakReference::create($this->object);
    }
}

// Covariant returns and contravariant parameters
interface Animal
{
    public function food(): Food;
}

interface Food {}

class Dog implements Animal
{
    // Covariant return type
    public function food(): DogFood
    {
        return new DogFood();
    }
}

class DogFood implements Food {}

// __serialize and __unserialize magic methods
class CustomSerializable
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function __serialize(): array
    {
        return ['data' => $this->data];
    }

    public function __unserialize(array $data): void
    {
        $this->data = $data['data'];
    }
}

// strip_tags with array of allowed tags
function stripTagsArray(string $html): string
{
    return strip_tags($html, ['p', 'a', 'strong']);
}
