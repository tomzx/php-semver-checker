<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PHPSemVerChecker\Comparator\Type;
use PHPSemVerChecker\Test\TestCase;

class TypeComparatorTest extends TestCase
{
	/**
	 * @dataProvider isSameProvider
	 */
	public function testIsSame($typeA, $typeB)
	{
		$this->assertTrue(Type::isSame($typeA, $typeB));
	}

	public function isSameProvider()
	{
		return [
			[Name::concat(null, 'test'), Name::concat(null, 'test')],
			['test', 'test'],
			[null, null]
		];
	}

	/**
	 * @dataProvider isNotSameProvider
	 */
	public function testIsNotSame($typeA, $typeB)
	{
		$this->assertFalse(Type::isSame($typeA, $typeB));
	}

	public function isNotSameProvider()
	{
		return [
			[Name::concat(null, 'test'), Name::concat(null, 'test1')],
			['test', 'test1'],
			[null, 'test']
		];
	}

	/**
	 * @dataProvider getProvider
	 */
	public function testGet($type, $expected)
	{
		$this->assertSame($expected, Type::get($type));
	}

	public function getProvider()
	{
		return [
			[null, null],
			['test', 'test'],
			[Name::concat('namespaced', 'test'), 'namespaced\test'],
			[new NullableType('test'), '?test'],
			[new NullableType(Name::concat('namespaced', 'test')), '?namespaced\test'],
		];
	}
}
