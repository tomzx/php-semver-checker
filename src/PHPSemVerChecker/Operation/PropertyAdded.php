<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\SemanticVersioning\Level;

class PropertyAdded extends PropertyOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V019', 'V020', 'V026'],
		'trait' => ['V049', 'V050', 'V055'],
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
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $propertyAfter;

	/**
	 * @param string                           $context
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt             $contextAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $propertyAfter
	 */
	public function __construct($context, $fileAfter, Stmt $contextAfter, Stmt\Property $propertyAfter)
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
		$fqcn = $this->contextAfter->name;
		if ($this->contextAfter->namespacedName) {
			$fqcn = $this->contextAfter->namespacedName->toString();
		}
		return $fqcn . '::$' . $this->propertyAfter->props[0]->name;
	}
}
