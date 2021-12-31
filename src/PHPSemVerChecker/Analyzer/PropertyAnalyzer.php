<?php
declare(strict_types=1);

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
	 * @param string      $context
	 * @param string|null $fileBefore
	 * @param string|null $fileAfter
	 */
	public function __construct(string $context, string $fileBefore = null, string $fileAfter = null)
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
	public function analyze(Stmt $contextBefore, Stmt $contextAfter): Report
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

	/**
	 * @param \PhpParser\Node\Stmt $context
	 * @return array
	 */
	protected function getProperties(Stmt $context): array
	{
		$properties = [];
		foreach ($context->stmts as $stmt) {
			if ($stmt instanceof Property) {
				$properties[] = $stmt;
			}
		}
		return $properties;
	}

	/**
	 * @param \PhpParser\Node\Stmt\Property $property
	 * @return string
	 */
	protected function getName(Property $property): string
	{
		return $property->props[0]->name->toString();
	}
}
