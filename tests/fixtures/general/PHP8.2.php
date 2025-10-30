<?php

// PHP 8.2 Features

// Readonly classes
readonly class Configuration
{
	public function __construct(
		public string $host,
		public int $port,
		public string $database,
	) {}
}

// Standalone true/false/null types
function alwaysTrue(): true
{
	return true;
}

function alwaysFalse(): false
{
	return false;
}

function alwaysNull(): null
{
	return null;
}

// DNF (Disjunctive Normal Form) types
interface A {}
interface B {}
interface C {}
interface D {}

class DNFExample
{
	public function process((A&B)|(C&D) $input): void
	{
		// Process input
	}
}

// Constants in traits
trait Configurable
{
	public const CONFIG_KEY = 'config';
	private const SECRET = 'secret123';
}

class Application
{
	use Configurable;
}

// Deprecate dynamic properties
#[\AllowDynamicProperties]
class DynamicClass
{
	public string $defined;
}

// Without the attribute, dynamic properties are deprecated
class StrictClass
{
	public string $defined;
}

// Random extension improvements
function generateRandomBytes(): string
{
	return random_bytes(16);
}
