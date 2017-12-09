<?php

function testScalarTypes(string $test)
{

}

function testReturnType(): array
{
	return [];
}

function testNullCoalescingOperator()
{
	return 1 ?? 2;
}

function testSpaceshipOperator()
{
	return 1 <=> 1;
}

function testAnonymousClass()
{
	new class {
		public function test()
		{

		}
	};
}
