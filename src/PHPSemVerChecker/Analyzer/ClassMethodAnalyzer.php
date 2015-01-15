<?php

namespace PHPSemVerChecker\Analyzer;

use PhpParser\Node\Stmt;
use PHPSemVerChecker\Comparator\Signature;
use PHPSemVerChecker\Operation\ClassMethodAdded;
use PHPSemVerChecker\Operation\ClassMethodImplementationChanged;
use PHPSemVerChecker\Operation\ClassMethodParameterChanged;
use PHPSemVerChecker\Operation\ClassMethodParameterNameChanged;
use PHPSemVerChecker\Operation\ClassMethodRemoved;
use PHPSemVerChecker\Report\Report;

class ClassMethodAnalyzer {
	protected $context;
	protected $fileBefore;
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

				// Signature
				$signatureChanged = false;
				if ( ! Signature::isSameTypehints($paramsBefore, $paramsAfter)) {
					$data = new ClassMethodParameterChanged($this->context, $this->fileBefore, $contextBefore, $methodBefore, $this->fileAfter, $contextAfter, $methodAfter);
					$report->add($this->context, $data);
					$signatureChanged = true;
				}

				if ( ! $signatureChanged && ! Signature::isSameVariables($paramsBefore, $paramsAfter)) {
					$data = new ClassMethodParameterNameChanged($this->context, $this->fileBefore, $contextBefore, $methodBefore, $this->fileAfter, $contextAfter, $methodAfter);
					$report->add($this->context, $data);
				}

				// Different length (considering params with defaults)

				// Difference in source code
				if ($methodBefore->stmts != $methodAfter->stmts) {
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
