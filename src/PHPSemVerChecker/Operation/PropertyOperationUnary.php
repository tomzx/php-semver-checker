<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property;
use PHPSemVerChecker\Node\Statement\Property as PProperty;

class PropertyOperationUnary extends PropertyOperation {
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt
	 */
	protected $propertyContext;
	/**
	 * @var \PhpParser\Node\Stmt\Property
	 */
	protected $property;

	/**
	 * @param string                        $context
	 * @param string                        $file
	 * @param \PhpParser\Node\Stmt          $propertyContex
	 * @param \PhpParser\Node\Stmt\Property $property
	 */
	public function __construct($context, $file, Stmt $propertyContext, Property $property)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($property);
		$this->file = $file;
		$this->propertyContext = $propertyContext;
		$this->property = $property;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->property->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PProperty::getFullyQualifiedName($this->propertyContext, $this->property);
	}
}
