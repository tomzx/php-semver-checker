<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\InterfaceAdded;
use PHPSemVerChecker\Operation\InterfaceRemoved;
use PHPSemVerChecker\Operation\InterfaceRenamedCaseOnly;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class InterfaceAnalyzer {
	/**
	 * @var string
	 */
	protected $context = 'interface';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$interfacesBefore = $registryBefore->data['interface'];
		$interfacesAfter = $registryAfter->data['interface'];

		$interfacesBeforeKeyed = [];
		$mappingsBeforeKeyed = [];
		foreach($interfacesBefore as $key => $interfaceBefore)
		{
			$interfacesBeforeKeyed[strtolower($interfaceBefore->name)] = $interfaceBefore;
			$mappingsBeforeKeyed[strtolower($interfaceBefore->name)] = $registryBefore->mapping['interface'][$key];
		}

		$interfacesAfterKeyed = [];
		$mappingsAfterKeyed = [];
		foreach($interfacesAfter as $key => $interfaceAfter)
		{
			$interfacesAfterKeyed[strtolower($interfaceAfter->name)] = $interfaceAfter;
			$mappingsAfterKeyed[strtolower($interfaceAfter->name)] = $registryAfter->mapping['interface'][$key];
		}

		$interfaceNamesBefore = array_keys($interfacesBeforeKeyed);
		$interfaceNamesAfter = array_keys($interfacesAfterKeyed);
		$added = array_diff($interfaceNamesAfter, $interfaceNamesBefore);
		$removed = array_diff($interfaceNamesBefore, $interfaceNamesAfter);
		$toVerify = array_intersect($interfaceNamesBefore, $interfaceNamesAfter);

		foreach ($removed as $key) {
			$fileBefore = $mappingsBeforeKeyed[$key];
			$interfaceBefore = $interfacesBeforeKeyed[$key];

			$data = new InterfaceRemoved($fileBefore, $interfaceBefore);
			$report->addInterface($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $mappingsBeforeKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceBefore = $interfacesBeforeKeyed[$key];
			$fileAfter = $mappingsAfterKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceAfter = $interfacesAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($interfaceBefore != $interfaceAfter) {

				// Check if the name of the interface has changed case.
				if($interfaceBefore->name !== $interfaceAfter->name)
				{
					$report->add('interface', new InterfaceRenamedCaseOnly($fileAfter, $interfaceAfter));
				}

				$analyzer = new ClassMethodAnalyzer('interface', $fileBefore, $fileAfter);
				$interfaceMethodReport = $analyzer->analyze($interfaceBefore, $interfaceAfter);
				$report->merge($interfaceMethodReport);
			}
		}

		foreach ($added as $key) {

			$fileAfter = $mappingsAfterKeyed[$key];
			$interfaceAfter = $interfacesAfterKeyed[$key];

			$data = new InterfaceAdded($fileAfter, $interfaceAfter);
			$report->addInterface($data);
		}

		return $report;
	}
}
