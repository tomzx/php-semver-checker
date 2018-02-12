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

class ClassMethodAnalyzer
{
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
			$methodsBeforeKeyed[strtolower($method->name)] = $method;
		}

		$methodsAfterKeyed = [];
		foreach ($methodsAfter as $method) {
			$methodsAfterKeyed[strtolower($method->name)] = $method;
		}

		// Here we only care about public methods as they are the only part of the API we care about

		$methodNamesNotAddedOrRemoved = [];

		foreach ($methodsBefore as $methodBefore) {
			// Removed methods can either be implemented in parent classes or not exist anymore
			if ($this->wasMethodRemoved($methodBefore, $methodsAfter)) {
				$data = new ClassMethodRemoved($this->context, $this->fileBefore, $contextBefore, $methodBefore);
				$report->add($this->context, $data);
			} else {
				$methodNamesNotAddedOrRemoved[strtolower($methodBefore->name)] = true;
			}
		}

		foreach ($methodsAfter as $methodAfter) {
			// Added methods implies MINOR BUMP
			if ($this->wasMethodAdded($methodAfter, $methodsBefore)) {
				$data = new ClassMethodAdded($this->context, $this->fileAfter, $contextAfter, $methodAfter);
				$report->add($this->context, $data);
			} else {
				$methodNamesNotAddedOrRemoved[strtolower($methodAfter->name)] = true;
			}
		}

		foreach (array_keys($methodNamesNotAddedOrRemoved) as $methodName) {

			/** @var \PhpParser\Node\Stmt\ClassMethod $methodBefore */
			$methodBefore = $methodsBeforeKeyed[$methodName];
			/** @var \PhpParser\Node\Stmt\ClassMethod $methodAfter */
			$methodAfter = $methodsAfterKeyed[$methodName];

			if (!$this->areMethodsEqual($methodBefore, $methodAfter)) {

				$signatureResult = Signature::analyze($methodBefore, $methodAfter);

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
					if (!$signatureResult[$changeType]) {
						continue;
					}
					if (is_a($class, ClassMethodOperationUnary::class, true)) {
						$data = new $class($this->context, $this->fileAfter, $contextAfter, $methodAfter);
					} else {
						$data = new $class(
							$this->context,
							$this->fileBefore,
							$contextBefore,
							$methodBefore,
							$this->fileAfter,
							$contextAfter,
							$methodAfter
						);
					}
					$report->add($this->context, $data);
				}

				// Difference in source code
				// Cast to array because interfaces do not have stmts (= null)
				if (!Implementation::isSame((array)$methodBefore->stmts, (array)$methodAfter->stmts)) {
					$data = new ClassMethodImplementationChanged(
						$this->context,
						$this->fileBefore,
						$contextBefore,
						$methodBefore,
						$this->fileAfter,
						$contextAfter,
						$methodAfter
					);
					$report->add($this->context, $data);
				}
			}
		}

		return $report;
	}

	private function areMethodsEqual(Stmt\ClassMethod $methodBefore, Stmt\ClassMethod $methodAfter)
	{
		if ($methodBefore == $methodAfter) {
			return true;
		};

		return strtolower($methodBefore->name) === strtolower($methodAfter->name)
			&& $methodBefore->isPrivate() === $methodAfter->isPrivate()
			&& $methodBefore->isAbstract() === $methodAfter->isAbstract()
			&& $methodBefore->isFinal() === $methodAfter->isFinal()
			&& $methodBefore->isProtected() === $methodAfter->isProtected()
			&& $methodBefore->isPublic() === $methodAfter->isPublic()
			&& $methodBefore->isStatic() === $methodAfter->isStatic()
			&& $methodBefore->getReturnType() === $methodAfter->getReturnType()
			// statements are objects, cannot be compared with ===
			&& $methodBefore->getStmts() == $methodAfter->getStmts()
			&& $methodBefore->getParams() === $methodAfter->getParams()
			&& $methodBefore->returnsByRef() === $methodAfter->returnsByRef()
			&& $methodBefore->getType() === $methodAfter->getType()
			&& $methodBefore->getAttributes() === $methodAfter->getAttributes();
	}

	private function wasMethodAdded(Stmt\ClassMethod $method, $methodsAfter)
	{
		foreach ($methodsAfter as $methodAfter) {
			if (strtolower($method->name) == strtolower($methodAfter->name)) {
				return false;
			}
		}

		return true;
	}

	private function wasMethodRemoved(Stmt\ClassMethod $method, $methodsBefore)
	{
		foreach ($methodsBefore as $methodBefore) {
			if (strtolower($method->name) == strtolower($methodBefore->name)) {
				return false;
			}
		}

		return true;
	}
}
