<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property;
use PHPSemVerChecker\Node\Statement\Property as PProperty;

class PropertyAdded extends PropertyOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V019', 'V020', 'V026'],
		'trait' => ['V049', 'V050', 'V055'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Property has been added.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt
	 */
	protected $contextAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Property
	 */
	protected $propertyAfter;

	/**
	 * @param string                        $context
	 * @param string                        $fileAfter
	 * @param \PhpParser\Node\Stmt          $contextAfter
	 * @param \PhpParser\Node\Stmt\Property $propertyAfter
	 */
	public function __construct($context, $fileAfter, Stmt $contextAfter, Property $propertyAfter)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($propertyAfter);
		$this->fileAfter = $fileAfter;
		$this->contextAfter = $contextAfter;
		$this->propertyAfter = $propertyAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->propertyAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PProperty::getFullyQualifiedName($this->contextAfter, $this->propertyAfter);
	}
}
