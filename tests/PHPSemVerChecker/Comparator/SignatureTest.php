<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PHPSemVerChecker\Comparator\Signature;
use PHPSemVerChecker\Test\TestCase;

class SignatureTest extends TestCase
{
	public function testAnalyzeNoParameter()
	{
		$parametersA = [];
		$parametersB = [];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterAdded()
	{
		$parametersA = [
			new Param('a', null, 'A'),
		];
		$parametersB = [
			new Param('a', null, 'A'),
			new Param('b', new String_('someDefaultValue'), 'B'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_added'] = [
			0 => 1,
		];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterRemoved()
	{
		$parametersA = [
			new Param('a', null, 'A'),
			new Param('b', new String_('someDefaultValue'), 'B'),
		];
		$parametersB = [
			new Param('a', null, 'A'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_removed'] = [
			0 => 1,
		];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterRenamed()
	{
		$parametersA = [
			new Param('a', null, 'A'),
			new Param('b', new String_('someDefaultValue'), 'B'),
		];
		$parametersB = [
			new Param('c', null, 'A'),
			new Param('d', new String_('someDefaultValue'), 'B'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_renamed'] = [
			0 => ['before' => 'a', 'after' => 'c'],
			1 => ['before' => 'b', 'after' => 'd'],
		];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterRenamedX()
	{
		$parametersA = [
			new Param('a', null, 'A'),
			new Param('b', new String_('someDefaultValue'), 'B'),
		];
		$parametersB = [
			new Param('b', new String_('someDefaultValue'), 'B'),
			new Param('a', null, 'A'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		//$expected['parameter_renamed'] = [
		//	0 => ['before' => 'a', 'after' => 'c'],
		//	1 => ['before' => 'b', 'after' => 'd'],
		//];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterTypingAdded()
	{
		$parametersA = [
			new Param('a', null),
		];
		$parametersB = [
			new Param('a', null, 'A'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_typing_added'] = [
			0 => ['A'],
		];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterTypingRemoved()
	{
		$parametersA = [
			new Param('a', null, 'A'),
		];
		$parametersB = [
			new Param('a', null),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_typing_removed'] = [
			0 => ['A'],
		];
		$this->assertSame($expected, $result);
	}

	public function testAnalyzeParameterDefaultAdded()
	{
		$parametersA = [
			new Param('a'),
		];
		$parametersB = [
			new Param('a', new ConstFetch(new Name('null'))),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_default_added'] = [
			0 => [new ConstFetch(new Name('null'))],
		];
		$this->assertEquals($expected, $result);
	}

	public function testAnalyzeParameterDefaultRemoved()
	{
		$parametersA = [
			new Param('a', new ConstFetch(new Name('null'))),
		];
		$parametersB = [
			new Param('a'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_default_removed'] = [
			0 => [new ConstFetch(new Name('null'))],
		];
		$this->assertEquals($expected, $result);
	}

	public function testAnalyzeParameterDefaultValueChanged()
	{
		$parametersA = [
			new Param('a', new ConstFetch(new Name('null'))),
		];
		$parametersB = [
			new Param('a', new ConstFetch(new Name('true'))),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		$expected['parameter_default_value_changed'] = [
			0 => [new ConstFetch(new Name('null'))],
		];
		$this->assertEquals($expected, $result);
	}

	public function testAnalyzeParameterAddedWithDefault()
	{
		$parametersA = [];
		$parametersB = [
			new Param('b', new String_('someDefaultValue'), 'B'),
		];
		$result = Signature::analyze($parametersA, $parametersB);
		$expected = Signature::changeArray();
		//$expected['parameter_default_value_changed'] = [
		//	0 => [new ConstFetch(new Name('null'))],
		//];
		$this->assertEquals($expected, $result);
	}
}
