<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Comparator\Signature;

class SignatureTest extends \PHPUnit_Framework_TestCase
{
	public function testIdenticalSignaturesNoParameters()
	{
		$signature1 = new ClassMethod('testMethod1');
		$signature2 = new ClassMethod('testMethod1');

		$result = Signature::analyze($signature1, $signature2);

		$expectedResult = [
			'function_renamed' => false,
			'function_renamed_case_only' => false,
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
		$signature1 = new ClassMethod('testMethod1', [
			'params' => [
				new Param('testParameter')
			],
		]);


		$signature2 = new ClassMethod('testMethod1', [
			'params' => [
				new Param('testRenamedParameter')
			],
		]);

		$result = Signature::analyze($signature1, $signature2);

		$expectedResult = [
			'function_renamed' => false,
			'function_renamed_case_only' => false,
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
		$signature1 = new ClassMethod('testMethod1', [
			'params' => [
				new Param('testParameter'),
			],
		]);


		$signature2 = new ClassMethod('testMethod1', [
			'params' => [
				new Param('testParameter'),
				new Param('testAddedParameter'),
			],
		]);

		$result = Signature::analyze($signature1, $signature2);

		$expectedResult = [
			'function_renamed' => false,
			'function_renamed_case_only' => false,
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

	public function testMethodRenamed()
	{
		$signature1 = new ClassMethod('testMethod1', [
			'params' => [
				new Param('testParameter'),
			],
		]);


		$signature2 = new ClassMethod('testMethodRenamed', [
			'params' => [
				new Param('testParameter'),
			],
		]);

		$result = Signature::analyze($signature1, $signature2);

		$expectedResult = [
			'function_renamed' => true,
			'function_renamed_case_only' => false,
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

	public function testMethodRenamedCaseOnly()
	{
		$signature1 = new ClassMethod('testMethodOne', [
			'params' => [
				new Param('testParameter'),
			],
		]);


		$signature2 = new ClassMethod('testmethodone', [
			'params' => [
				new Param('testParameter'),
			],
		]);

		$result = Signature::analyze($signature1, $signature2);

		$expectedResult = [
			'function_renamed' => false,
			'function_renamed_case_only' => true,
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
}
