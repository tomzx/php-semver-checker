<?php

namespace PHPSemVerChecker\Analyzer;

use PhpParser\Node\Stmt;
use PHPSemVerChecker\Comparator\Implementation;
use PHPSemVerChecker\Comparator\Signature;
use PHPSemVerChecker\Operation\ClassMethodAdded;
use PHPSemVerChecker\Operation\ClassMethodImplementationChanged;
use PHPSemVerChecker\Operation\ClassMethodOperationUnary;
use PHPSemVerChecker\Operation\ClassMethodParameterAdded;
use PHPSemVerChecker\Operation\ClassMethodParameterDefaultAdded;
use PHPSemVerChecker\Operation\ClassMethodParameterDefaultRemoved;
use PHPSemVerChecker\Operation\ClassMethodParameterDefaultValueChanged;
use PHPSemVerChecker\Operation\ClassMethodParameterNameChanged;
use PHPSemVerChecker\Operation\ClassMethodParameterRemoved;
use PHPSemVerChecker\Operation\ClassMethodParameterTypingAdded;
use PHPSemVerChecker\Operation\ClassMethodParameterTypingRemoved;
use PHPSemVerChecker\Operation\ClassMethodRemoved;
use PHPSemVerChecker\Report\Report;

class ClassMethodAnalyzer {
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var null|string
	 */
	protected $fileBefore;
	/**
	 * @var null|string
	 */
	protected $fileAfter;

	/**
	 * @param string $context
	 * @param string $fileBefore
	 * @param string $fileAfter
	 */
	public function __construct($context, $fileBefore = null, $fileAfter = null)
	{
		$this->context = $context;
		$this->fileBefore = $fileBefore;
		$this->fileAfter = $fileAfter;
	}

	/**
	 * @param \PhpParser\Node\Stmt $contextBefore
	 * @param \PhpParser\Node\Stmt $contextAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Stmt $contextBefore, Stmt $contextAfter)
	{
		$report = new Report();

		$methodsBefore = $contextBefore->getMethods();
		$methodsAfter = $contextAfter->getMethods();

		$methodsBeforeKeyed = [];
		foreach ($methodsBefore as $method) {
			$methodsBeforeKeyed[$method->name] = $method;
		}

		$methodsAfterKeyed = [];
		foreach ($methodsAfter as $method) {
			$methodsAfterKeyed[$method->name] = $method;
		}

		$methodNamesBefore = array_keys($methodsBeforeKeyed);
		$methodNamesAfter = array_keys($methodsAfterKeyed);
		$methodsAdded = array_diff($methodNamesAfter, $methodNamesBefore);
		$methodsRemoved = array_diff($methodNamesBefore, $methodNamesAfter);
		$methodsToVerify = array_intersect($methodNamesBefore, $methodNamesAfter);

		// Here we only care about public methods as they are the only part of the API we care about

		// Removed methods can either be implemented in parent classes or not exist anymore
		foreach ($methodsRemoved as $method) {
			$methodBefore = $methodsBeforeKeyed[$method];
			$data = new ClassMethodRemoved($this->context, $this->fileBefore, $contextBefore, $methodBefore);
			$report->add($this->context, $data);
		}

		foreach ($methodsToVerify as $method) {
			/** @var \PhpParser\Node\Stmt\ClassMethod $methodBefore */
			$methodBefore = $methodsBeforeKeyed[$method];
			/** @var \PhpParser\Node\Stmt\ClassMethod $methodAfter */
			$methodAfter = $methodsAfterKeyed[$method];

			// Leave non-strict comparison here
			if ($methodBefore != $methodAfter) {
				$paramsBefore = $methodBefore->params;
				$paramsAfter = $methodAfter->params;

				$signatureResult = Signature::analyze($paramsBefore, $paramsAfter);

				$changes = [
					'parameter_added' => ClassMethodParameterAdded::class,
					'parameter_removed' => ClassMethodParameterRemoved::class,
					'parameter_renamed' => ClassMethodParameterNameChanged::class,
					'parameter_typing_added' => ClassMethodParameterTypingAdded::class,
					'parameter_typing_removed' => ClassMethodParameterTypingRemoved::class,
					'parameter_default_added' => ClassMethodParameterDefaultAdded::class,
					'parameter_default_removed' => ClassMethodParameterDefaultRemoved::class,
					'parameter_default_value_changed' => ClassMethodParameterDefaultValueChanged::class,
				];

				foreach ($changes as $changeType => $class) {
					if ( ! $signatureResult[$changeType]) {
						continue;
					}
					if (is_a($class, ClassMethodOperationUnary::class, true)) {
						$data = new $class($this->context, $this->fileAfter, $contextAfter, $methodAfter);
					} else {
						$data = new $class($this->context, $this->fileBefore, $contextBefore, $methodBefore, $this->fileAfter, $contextAfter, $methodAfter);
					}
					$report->add($this->context, $data);
				}

				// Difference in source code
				// Cast to array because interfaces do not have stmts (= null)
				if ( ! Implementation::isSame((array)$methodBefore->stmts, (array)$methodAfter->stmts)) {
					$data = new ClassMethodImplementationChanged($this->context, $this->fileBefore, $contextBefore, $methodBefore, $this->fileAfter, $contextAfter, $methodAfter);
					$report->add($this->context, $data);
				}
			}
		}

		// Added methods implies MINOR BUMP
		foreach ($methodsAdded as $method) {
			$methodAfter = $methodsAfterKeyed[$method];
			$data = new ClassMethodAdded($this->context, $this->fileAfter, $contextAfter, $methodAfter);
			$report->add($this->context, $data);
		}

		return $report;
	}
}
