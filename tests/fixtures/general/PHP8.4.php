<?php

// PHP 8.4 Features

// Property hooks
class User
{
	// Property with get hook
	public string $fullName {
		get => $this->firstName . ' ' . $this->lastName;
	}

	// Property with set hook
	private string $_email;
	public string $email {
		set {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				throw new \ValueError('Invalid email');
			}
			$this->_email = $value;
		}
		get => $this->_email;
	}

	public function __construct(
		public string $firstName,
		public string $lastName,
	) {}
}

// Asymmetric visibility
class BankAccount
{
	public private(set) float $balance = 0.0;

	public function deposit(float $amount): void
	{
		$this->balance += $amount;
	}

	public function getBalance(): float
	{
		return $this->balance;
	}
}

class Document
{
	public protected(set) string $status = 'draft';

	protected function updateStatus(string $newStatus): void
	{
		$this->status = $newStatus;
	}
}

// New array functions
function arrayFunctions(): void
{
	$array = [1, 2, 3, 4, 5];

	// array_find
	$first = array_find($array, fn($v) => $v > 2);

	// array_find_key
	$key = array_find_key($array, fn($v) => $v > 2);

	// array_all
	$allPositive = array_all($array, fn($v) => $v > 0);

	// array_any
	$hasEven = array_any($array, fn($v) => $v % 2 === 0);
}

// New without parentheses
class Service
{
	public function getInstance(): static
	{
		return new static;
	}

	public function createNew(): self
	{
		return new self;
	}
}

// Lazy objects
class ExpensiveResource
{
	public function __construct(
		private string $data,
	) {
		// Expensive initialization
	}
}

function createLazyObject(): object
{
	$reflector = new \ReflectionClass(ExpensiveResource::class);
	return $reflector->newLazyGhost(function ($object) {
		// Initialize when first accessed
	});
}
