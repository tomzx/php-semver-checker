<?php

namespace PHPSemVerChecker\Report;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use PHPSemVerChecker\Operation\Operation;
use PHPSemVerChecker\SemanticVersioning\Level;

class Report implements ArrayAccess, IteratorAggregate
{
	/**
	 * @var array
	 */
	protected $differences;

	public function __construct()
	{
		$levels = array_fill_keys(Level::asList(), []);

		$this->differences = [
			'class'     => $levels,
			'function'  => $levels,
			'interface' => $levels,
			'trait'     => $levels,
		];
	}

	/**
	 * @param \PHPSemVerChecker\Operation\Operation $classOperation
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function addClass(Operation $classOperation)
	{
		return $this->add('class', $classOperation);
	}

	/**
	 * @param \PHPSemVerChecker\Operation\Operation $functionOperation
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function addFunction(Operation $functionOperation)
	{
		return $this->add('function', $functionOperation);
	}

	/**
	 * @param \PHPSemVerChecker\Operation\Operation $interfaceOperation
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function addInterface(Operation $interfaceOperation)
	{
		return $this->add('interface', $interfaceOperation);
	}

	/**
	 * @param \PHPSemVerChecker\Operation\Operation $traitOperation
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function addTrait(Operation $traitOperation)
	{
		return $this->add('trait', $traitOperation);
	}

	/**
	 * @param string                                $context
	 * @param \PHPSemVerChecker\Operation\Operation $operation
	 * @return $this
	 */
	public function add($context, Operation $operation)
	{
		$level = $operation->getLevel();
		$this->differences[$context][$level][] = $operation;

		return $this;
	}

	/**
	 * @param \PHPSemVerChecker\Report\Report $report
	 * @return $this
	 */
	public function merge(Report $report)
	{
		foreach ($report->differences as $context => $levels) {
			foreach ($levels as $level => $differences) {
				$this->differences[$context][$level] = array_merge($this->differences[$context][$level], $differences);
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getDifferences()
	{
		return $this->differences;
	}

	/**
	 * @param string|array|null $context
	 * @param string|array|null $level
	 * @return bool
	 */
	public function hasDifferences($context = null, $level = null)
	{
		$queriedContexts = $context ? (array)$context : array_keys($this->differences);
		$queriedLevels = $level ? (array)$level : Level::asList('desc');
		foreach ($queriedContexts as $queriedContext) {
			foreach ($queriedLevels as $queriedLevel) {
				if ( ! empty($this->differences[$queriedContext][$queriedLevel])) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @param string|array|null $context
	 * @return int
	 */
	public function getLevelForContext($context = null)
	{
		$queriedContexts = $context ? (array)$context : array_keys($this->differences);
		$levels = Level::asList('desc');
		foreach ($queriedContexts as $queriedContext) {
			foreach ($levels as $level) {
				if ( ! empty($this->differences[$queriedContext][$level])) {
					return $level;
				}
			}
		}
		return Level::NONE;
	}

	/**
	 * @return int
	 */
	public function getSuggestedLevel()
	{
		foreach (Level::asList('desc') as $level) {
			foreach ($this->differences as $context => $levels) {
				if ( ! empty($levels[$level])) {
					return $level;
				}
			}
		}

		return Level::NONE;
	}

	// TODO: Get rid of ArrayAccess (temporary to transition) <tom@tomrochette.com>

	/**
	 * @param string $offset
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists($offset)
	{
		return isset($this->differences[$offset]);
	}

	/**
	 * @param string $offset
	 * @return array
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		return $this->differences[$offset];
	}

	/**
	 * @param string $offset
	 * @param mixed  $value
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet($offset, $value)
	{
		if ($offset === null) {
			$this->differences[] = $value;
		} else {
			$this->differences[$offset] = $value;
		}
	}

	/**
	 * @param string $offset
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset($offset)
	{
		unset($this->differences[$offset]);
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	#[\ReturnTypeWillChange]
	public function getIterator()
	{
		return new ArrayIterator($this->differences);
	}
}
