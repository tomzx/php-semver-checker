<?php

namespace PHPSemVerChecker\Registry;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Operation\ClassAdded;
use PHPSemVerChecker\Operation\ClassMethodAdded;
use PHPSemVerChecker\Operation\ClassMethodImplementationChanged;
use PHPSemVerChecker\Operation\ClassMethodParameterMismatch;
use PHPSemVerChecker\Operation\ClassMethodRemoved;
use PHPSemVerChecker\Operation\ClassRemoved;
use PHPSemVerChecker\Operation\FunctionAdded;
use PHPSemVerChecker\Operation\FunctionImplementationChanged;
use PHPSemVerChecker\Operation\FunctionParameterMismatch;
use PHPSemVerChecker\Operation\FunctionRemoved;
use PHPSemVerChecker\Operation\Operation;
use PHPSemVerChecker\Operation\Unknown;

class Registry {
	const NONE = 0;
	const PATCH = 1;
	const MINOR = 2;
	const MAJOR = 3;
	/**
	 * @var array
	 */
	protected $data;
	/**
	 * @var array
	 */
	protected $mapping;
	/**
	 * @var string
	 */
	protected $currentFile;

	public function __construct()
	{
		$this->data = [
			'function' => [],
			'class'    => [],
		];

		$this->mapping = [
			'function' => [],
			'class'    => [],
		];
	}

	// TODO: Find a way to hash items of different types for fast retrieval <tom@tomrochette.com>
	// Is FQCN + function name enough?
	// TODO: Try to reduce the amount of data moved around (we don't need statements inside methods/functions) <tom@tomrochette.com>
	/**
	 * @param \PhpParser\Node\Stmt\Class_ $item
	 */
	public function addClass(Class_ $item)
	{
		$fqcn = $item->name;
		if ($item->namespacedName) {
			$fqcn = $item->namespacedName->toString();
		}
		$this->data['class'][$fqcn] = $item;
		$this->mapping['class'][$fqcn] = $this->getCurrentFile();
	}

	/**
	 * @param \PhpParser\Node\Stmt\Function_ $item
	 */
	public function addFunction(Function_ $item)
	{
		$fqfn = $item->name;
		if ($item->namespacedName) {
			$fqfn = $item->namespacedName->toString();
		}
		$this->data['function'][$fqfn] = $item;
		$this->mapping['function'][$fqfn] = $this->getCurrentFile();
	}

	/**
	 * @param string $file
	 */
	public function setCurrentFile($file)
	{
		$this->currentFile = realpath($file);
	}

	/**
	 * @return string
	 */
	public function getCurrentFile()
	{
		return $this->currentFile;
	}

	/**
	 * Compare with a destination registry (what the new source code is like).
	 *
	 * @param \PHPSemVerChecker\Registry\Registry $registry
	 * @return array
	 */
	public function compare(Registry $registry)
	{
		$differences = [
			'function' => [
				self::NONE  => [],
				self::PATCH => [],
				self::MINOR => [],
				self::MAJOR => [],
			],
			'class'    => [
				self::NONE  => [],
				self::PATCH => [],
				self::MINOR => [],
				self::MAJOR => [],
			],
		];

		$appendDifference = function ($type, $level, Operation $data) use (&$differences, &$changeType) {
			$differences[$type][$level][] = [
				'reason'   => $data->getReason(),
				'location' => $data->getLocation(),
				'data'     => $data,
			];
			if ($level > $changeType) {
				$changeType = $level;
			}
		};

		$appendFunctionDifference = function ($level, $data) use ($appendDifference) {
			$appendDifference('function', $level, $data);
		};

		$appendClassDifference = function ($level, $data) use ($appendDifference) {
			$appendDifference('class', $level, $data);
		};

		$keysBefore = array_keys($this->data['function']);
		$keysAfter = array_keys($registry->data['function']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_diff($keysBefore, $added, $removed);

		foreach ($removed as $key) {
			$fileBefore = $this->mapping['function'][$key];
			$functionBefore = $this->data['function'][$key];

			$data = new FunctionRemoved($fileBefore, $functionBefore);
			$appendFunctionDifference(self::MAJOR, $data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $this->mapping['function'][$key];
			/** @var Function_ $functionBefore */
			$functionBefore = $this->data['function'][$key];
			$fileAfter = $registry->mapping['function'][$key];
			$functionAfter = $registry->data['function'][$key];

			// TODO: Verify this comparison works properly <tom@tomrochette.com>
			// Leave non-strict comparison here
			if ($functionBefore != $functionAfter) {
				$paramsBefore = $functionBefore->params;
				$paramsAfter = $functionAfter->params;
				// Signature

				// Argument order is different (type mismatch)
				$iterations = min(count($paramsBefore), count($paramsAfter));
				for ($i = 0; $i < $iterations; ++$i) {
					$paramTypeBefore = is_object($paramsBefore[$i]->type) ? $paramsBefore[$i]->type->toString() : $paramsBefore[$i]->type;
					$paramTypeAfter = is_object($paramsAfter[$i]->type) ? $paramsAfter[$i]->type->toString() : $paramsAfter[$i]->type;
					// TODO: Allow for contravariance <tom@tomrochette.com>
					if ($paramTypeBefore !== $paramTypeAfter) {
						$data = new FunctionParameterMismatch($fileBefore, $functionBefore, $fileBefore, $functionAfter);
						$appendFunctionDifference(self::MAJOR, $data);
						continue 2;
					}
				}

				// Different length (considering params with defaults)

				// Difference in source code
				if ($functionBefore->stmts != $functionAfter->stmts) {
					$appendFunctionDifference(self::PATCH, new FunctionImplementationChanged($fileBefore, $functionBefore, $fileAfter, $functionAfter));
					continue;
				}

				// Unable to match an issue, but there is one...
				$appendFunctionDifference(self::MAJOR, new Unknown($fileBefore, $fileAfter));
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registry->mapping['function'][$key];
			$functionAfter = $registry->data['function'][$key];

			$data = new FunctionAdded($fileAfter, $functionAfter);
			$appendFunctionDifference(self::MINOR, $data);
		}

		$keysBefore = array_keys($this->data['class']);
		$keysAfter = array_keys($registry->data['class']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_diff($keysBefore, $added, $removed);

		foreach ($removed as $key) {
			$fileBefore = $this->mapping['class'][$key];
			$classBefore = $this->data['class'][$key];

			$data = new ClassRemoved($fileBefore, $classBefore);
			$appendClassDifference(self::MAJOR, $data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $this->mapping['class'][$key];
			/** @var Class_ $classBefore */
			$classBefore = $this->data['class'][$key];
			$fileAfter = $registry->mapping['class'][$key];
			$classAfter = $registry->data['class'][$key];

			// TODO: Verify this comparison works properly <tom@tomrochette.com>
			// Leave non-strict comparison here
			if ($classBefore != $classAfter) {
				$methodsBefore = $classBefore->getMethods();
				$methodsAfter = $classAfter->getMethods();

				$methodsBeforeKeyed = [];
				foreach ($methodsBefore as $method) {
					$methodsBeforeKeyed[$method->name] = $method;
				}

				$methodsAfterKeyed = [];
				foreach ($methodsAfter as $method) {
					$methodsAfterKeyed[$method->name] = $method;
				}

				$methodsBeforeKeyed = array_filter($methodsBeforeKeyed, function (ClassMethod $method) {
					return $method->isPublic();
				});

				$methodsAfterKeyed = array_filter($methodsAfterKeyed, function (ClassMethod $method) {
					return $method->isPublic();
				});

				$methodNamesBefore = array_keys($methodsBeforeKeyed);
				$methodNamesAfter = array_keys($methodsAfterKeyed);
				$methodsAdded = array_diff($methodNamesAfter, $methodNamesBefore);
				$methodsRemoved = array_diff($methodNamesBefore, $methodNamesAfter);
				$methodsToVerify = array_diff($methodNamesBefore, $methodsAdded, $methodsRemoved);

				// Here we only care about public methods as they are the only part of the API we care about

				// Removed methods can either be implemented in parent classes or not exist anymore
				foreach ($methodsRemoved as $method) {
					$methodBefore = $methodsBeforeKeyed[$method];
					$data = new ClassMethodRemoved($fileBefore, $classBefore, $methodBefore);
					$appendClassDifference(self::MAJOR, $data);
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

						// Argument order is different (type mismatch)
						$iterations = min(count($paramsBefore), count($paramsAfter));
						for ($i = 0; $i < $iterations; ++$i) {
							$paramTypeBefore = is_object($paramsBefore[$i]->type) ? $paramsBefore[$i]->type->toString() : $paramsBefore[$i]->type;
							$paramTypeAfter = is_object($paramsAfter[$i]->type) ? $paramsAfter[$i]->type->toString() : $paramsAfter[$i]->type;
							// TODO: Allow for contravariance <tom@tomrochette.com>
							// TODO: Check that this works properly with aliases <tom@tomrochette.com>
							if ($paramTypeBefore !== $paramTypeAfter) {
								$data = new ClassMethodParameterMismatch($fileBefore, $methodBefore, $fileBefore, $methodAfter);
								$appendClassDifference(self::MAJOR, $data);
								continue 2;
							}
						}

						// Different length (considering params with defaults)

						// Difference in source code
						if ($methodBefore->stmts != $methodAfter->stmts) {
							$appendClassDifference(self::PATCH, new ClassMethodImplementationChanged($fileBefore, $methodBefore, $fileAfter, $methodAfter));
							continue;
						}

						// Unable to match an issue, but there is one...
						$appendClassDifference(self::MAJOR, new Unknown($fileBefore, $fileAfter));
					}
				}

				// Added methods implies MINOR BUMP
				foreach ($methodsAdded as $method) {
					$methodAfter = $methodsAfterKeyed[$method];
					$data = new ClassMethodAdded($fileAfter, $classAfter, $methodAfter);
					$appendClassDifference(self::MINOR, $data);
				}
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registry->mapping['class'][$key];
			$classAfter = $registry->data['class'][$key];

			$data = new ClassAdded($fileAfter, $classAfter);
			$appendClassDifference(self::MINOR, $data);
		}

		return $differences;
	}

	/**
	 * @param $level
	 * @return string
	 */
	public static function levelToString($level)
	{
		$mapping = [
			self::NONE  => 'NONE',
			self::PATCH => 'PATCH',
			self::MINOR => 'MINOR',
			self::MAJOR => 'MAJOR',
		];

		return $mapping[$level];
	}
}