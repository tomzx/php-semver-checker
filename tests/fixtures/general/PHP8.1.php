<?php

// PHP 8.1 Features

// Enums
enum Status: string
{
	case Pending = 'pending';
	case Active = 'active';
	case Archived = 'archived';
}

enum Priority
{
	case Low;
	case Medium;
	case High;
}

// Readonly properties
class User
{
	public function __construct(
		public readonly string $name,
		public readonly int $id,
	) {}
}

// Intersection types
interface Loggable {}
interface Cacheable {}

class Service
{
	public function process(Loggable&Cacheable $object): void
	{
		// Process object
	}
}

// Never return type
function redirect(string $url): never
{
	header("Location: $url");
	exit();
}

// First-class callable syntax
class Calculator
{
	public function add(int $a, int $b): int
	{
		return $a + $b;
	}
}

$calc = new Calculator();
$addFunction = $calc->add(...);

// New in initializers
class ServiceContainer
{
	public function __construct(
		private Logger $logger = new Logger(),
	) {}
}

class Logger
{
	public function log(string $message): void
	{
		echo $message;
	}
}

// Final class constants
class Config
{
	final public const APP_NAME = 'MyApp';
}
