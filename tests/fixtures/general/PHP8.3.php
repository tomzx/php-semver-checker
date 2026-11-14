<?php

// PHP 8.3 Features

// Typed class constants
class HttpStatus
{
	public const int OK = 200;
	public const int NOT_FOUND = 404;
	public const int SERVER_ERROR = 500;

	public const string VERSION = '1.0.0';
	public const array METHODS = ['GET', 'POST', 'PUT', 'DELETE'];
}

interface StatusInterface
{
	public const int STATUS_CODE = 0;
}

// Readonly anonymous classes
$config = new readonly class {
	public function __construct(
		public string $name = 'default',
		public int $timeout = 30,
	) {}
};

// Override attribute
class ParentClass
{
	public function process(): void
	{
		echo "Parent process";
	}
}

class ChildClass extends ParentClass
{
	#[\Override]
	public function process(): void
	{
		echo "Child process";
	}
}

// Negative indices in arrays
function arrayFeatures(): void
{
	$array = ['a', 'b', 'c'];
	// Can now use negative indices
	$last = $array[-1] ?? null;
}

// Dynamic class constant fetch
class DynamicConstants
{
	public const VALUE_A = 'A';
	public const VALUE_B = 'B';
}

function getConstant(string $name): string
{
	return DynamicConstants::{$name};
}

// json_validate function
function validateJson(string $json): bool
{
	return json_validate($json);
}

// Randomizer class additions
function randomFeatures(): void
{
	$randomizer = new \Random\Randomizer();
	$bytes = $randomizer->getBytes(16);
	$int = $randomizer->getInt(1, 100);
}
