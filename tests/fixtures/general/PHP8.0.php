<?php

// PHP 8.0 Features

// Named arguments
function createUser(string $name, string $email, int $age, bool $active = true): array
{
    return compact('name', 'email', 'age', 'active');
}

$user = createUser(
    name: 'John Doe',
    email: 'john@example.com',
    age: 30,
    active: false,
);

// Union types
class ResponseHandler
{
    public function process(int|float $number): int|float
    {
        return $number * 2;
    }

    public function format(string|null $text): string
    {
        return $text ?? 'default';
    }
}

// Constructor property promotion
class Point
{
    public function __construct(
        public float $x = 0.0,
        public float $y = 0.0,
        public float $z = 0.0,
    ) {}
}

class Product
{
    public function __construct(
        private string $name,
        private float $price,
        protected int $stock = 0,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}

// Attributes (Annotations)
#[Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET',
    ) {}
}

#[Route('/api/users', method: 'POST')]
class UserController
{
    #[Route('/api/users/{id}', method: 'GET')]
    public function show(int $id): array
    {
        return ['id' => $id];
    }
}

// Match expression
function getStatusMessage(int $code): string
{
    return match($code) {
        200 => 'OK',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        default => 'Unknown Status',
    };
}

function calculate(string $operator, int $a, int $b): int
{
    return match($operator) {
        '+' => $a + $b,
        '-' => $a - $b,
        '*' => $a * $b,
        '/' => $a / $b,
    };
}

// Nullsafe operator
class Address
{
    public function __construct(public ?string $street = null) {}
}

class Customer
{
    public function __construct(public ?Address $address = null) {}
}

function getStreet(Customer $customer): ?string
{
    return $customer?->address?->street;
}

// Mixed type
function handleAny(mixed $value): mixed
{
    return $value;
}

// Static return type
class Factory
{
    public static function create(): static
    {
        return new static();
    }
}

// throw as expression
function getConfigValue(string $key): string
{
    return $_ENV[$key] ?? throw new \Exception('Config key not found');
}

// str_contains, str_starts_with, str_ends_with
function stringFunctions(string $text): void
{
    $hasWord = str_contains($text, 'word');
    $startsWithHello = str_starts_with($text, 'Hello');
    $endsWithWorld = str_ends_with($text, 'World');
}

// WeakMap
class WeakMapExample
{
    private \WeakMap $cache;

    public function __construct()
    {
        $this->cache = new \WeakMap();
    }

    public function cache(object $key, mixed $value): void
    {
        $this->cache[$key] = $value;
    }
}

// Non-capturing catches
function handleException(): void
{
    try {
        throw new \Exception('Error');
    } catch (\Exception) {
        // No variable needed
        echo 'Caught exception';
    }
}

// Allow trailing comma in parameter lists
function multipleParams(
    string $a,
    string $b,
    string $c,
): void {
    // Function body
}
