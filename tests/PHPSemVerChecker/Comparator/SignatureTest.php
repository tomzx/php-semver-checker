<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Comparator\Signature;

class SignatureTest extends \PHPUnit_Framework_TestCase
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
		$params1 = [new Param('testParameter')];
		$params2 = [new Param('testRenamedParameter')];

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
			new Param('testParameter'),
		];

		$params2 = [
			new Param('testParameter'),
			new Param('testAddedParameter'),
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
