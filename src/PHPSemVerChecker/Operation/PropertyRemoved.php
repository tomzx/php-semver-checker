<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPSemVerChecker\SemanticVersioning\Level;

class PropertyRemoved extends PropertyOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V008', 'V009', 'V027'],
		'trait' => ['V040',  'V041',  'V056'],
	];
	/**
	 * @var int
	 */
	protected $level = [
		'class' => [Level::MAJOR, Level::MAJOR, Level::PATCH],
		'trait' => [Level::MAJOR, Level::MAJOR, Level::MAJOR],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Property has been removed.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var Class_
	 */
	protected $contextBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Property
	 */
	protected $propertyBefore;

	/**
	 * @param string                        $context
	 * @param string                        $fileBefore
	 * @param \PhpParser\Node\Stmt          $contextBefore
	 * @param \PhpParser\Node\Stmt\Property $propertyBefore
	 */
	public function __construct($context, $fileBefore, Stmt $contextBefore, Property $propertyBefore)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($propertyBefore);
		$this->fileBefore = $fileBefore;
		$this->contextBefore = $contextBefore;
		$this->propertyBefore = $propertyBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->propertyBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->contextBefore->name;
		if ($this->contextBefore->namespacedName) {
			$fqcn = $this->contextBefore->namespacedName->toString();
		}
		return $fqcn . '::$' . $this->propertyBefore->props[0]->name;
	}
}
