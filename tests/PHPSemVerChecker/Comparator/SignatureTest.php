<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Comparator\Signature;
use PHPUnit\Framework\TestCase;

class SignatureTest extends TestCase
{
	public function testIdenticalSignaturesNoParameters()
	{
		$result = Signature::analyze([], []);

		$expectedResult = [
			'parameter_added' => false,
			'parameter_removed' => false,
			'parameter_renamed' => false,
			'parameter_typing_added' => false,
			'parameter_typing_removed' => false,
			'parameter_default_added' => false,
			'parameter_default_removed' => false,
			'parameter_default_value_changed' => false,
		];

		$this->assertEquals($expectedResult, $result);
	}

	public function testRenamedParameters()
	{
		$params1 = [new Param(new Variable('testParameter'))];
		$params2 = [new Param(new Variable('testRenamedParameter'))];

		$result = Signature::analyze($params1, $params2);

		$expectedResult = [
			'parameter_added' => false,
			'parameter_removed' => false,
			'parameter_renamed' => true,
			'parameter_typing_added' => false,
			'parameter_typing_removed' => false,
			'parameter_default_added' => false,
			'parameter_default_removed' => false,
			'parameter_default_value_changed' => false,
		];

		$this->assertEquals($expectedResult, $result);
	}

	public function testAddedParameters()
	{
		$params1 = [
			new Param(new Variable('testParameter')),
		];

		$params2 = [
			new Param(new Variable('testParameter')),
			new Param(new Variable('testAddedParameter')),
		];

		$result = Signature::analyze($params1, $params2);

		$expectedResult = [
			'parameter_added' => true,
			'parameter_removed' => false,
			'parameter_renamed' => false,
			'parameter_typing_added' => false,
			'parameter_typing_removed' => false,
			'parameter_default_added' => false,
			'parameter_default_removed' => false,
			'parameter_default_value_changed' => false,
		];

		$this->assertEquals($expectedResult, $result);
	}
}
