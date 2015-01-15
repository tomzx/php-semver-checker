<?php

namespace PHPSemVerChecker\Analyzer;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property;
use PHPSemVerChecker\Operation\PropertyAdded;
use PHPSemVerChecker\Operation\PropertyRemoved;
use PHPSemVerChecker\Report\Report;

class PropertyAnalyzer
{
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

	public function analyze(Stmt $contextBefore, Stmt $contextAfter)
	{
		$report = new Report();

		$propertiesBefore = $this->getProperties($contextBefore);
		$propertiesAfter = $this->getProperties($contextAfter);

		$propertiesBeforeKeyed = [];
		foreach ($propertiesBefore as $property) {
			$propertiesBeforeKeyed[$this->getName($property)] = $property;
		}

		$propertiesAfterKeyed = [];
		foreach ($propertiesAfter as $property) {
			$propertiesAfterKeyed[$this->getName($property)] = $property;
		}

		$propertyNamesBefore = array_keys($propertiesBeforeKeyed);
		$propertyNamesAfter = array_keys($propertiesAfterKeyed);
		$propertiesAdded = array_diff($propertyNamesAfter, $propertyNamesBefore);
		$propertiesRemoved = array_diff($propertyNamesBefore, $propertyNamesAfter);
		$propertiesToVerify = array_intersect($propertyNamesBefore, $propertyNamesAfter);

		foreach ($propertiesRemoved as $property) {
			$propertyBefore = $propertiesBeforeKeyed[$property];
			$data = new PropertyRemoved($this->context, $this->fileBefore, $contextBefore, $propertyBefore);
			$report->add($this->context, $data);
		}

		foreach ($propertiesAdded as $property) {
			$propertyAfter = $propertiesAfterKeyed[$property];
			$data = new PropertyAdded($this->context, $this->fileAfter, $contextAfter, $propertyAfter);
			$report->add($this->context, $data);
		}

		return $report;
	}

	protected function getProperties(Stmt $context)
	{
		$properties = [];
		foreach ($context->stmts as $stmt) {
			if ($stmt instanceof Property) {
				$properties[] = $stmt;
			}
		}
		return $properties;
	}

	protected function getName(Property $property)
	{
		return $property->props[0]->name;
	}
}
