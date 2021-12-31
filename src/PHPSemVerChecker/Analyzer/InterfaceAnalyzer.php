<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\InterfaceAdded;
use PHPSemVerChecker\Operation\InterfaceCaseChanged;
use PHPSemVerChecker\Operation\InterfaceRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class InterfaceAnalyzer
{
	/**
	 * @var string
	 */
	protected $context = 'interface';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter): Report
	{
		$report = new Report();

		$interfacesBefore = $registryBefore->data['interface'];
		$interfacesAfter = $registryAfter->data['interface'];

		$filesBeforeKeyed = [];
		$interfacesBeforeKeyed = [];
		foreach ($interfacesBefore as $key => $interfaceBefore) {
			$filesBeforeKeyed[strtolower($key)] = $registryBefore->mapping['interface'][$key];
			$interfacesBeforeKeyed[strtolower($key)] = $interfaceBefore;
		}

		$filesAfterKeyed = [];
		$interfacesAfterKeyed = [];
		foreach ($interfacesAfter as $key => $interfaceAfter) {
			$filesAfterKeyed[strtolower($key)] = $registryAfter->mapping['interface'][$key];
			$interfacesAfterKeyed[strtolower($key)] = $interfaceAfter;
		}

		$interfaceNamesBefore = array_keys($interfacesBeforeKeyed);
		$interfaceNamesAfter = array_keys($interfacesAfterKeyed);
		$added = array_diff($interfaceNamesAfter, $interfaceNamesBefore);
		$removed = array_diff($interfaceNamesBefore, $interfaceNamesAfter);
		$toVerify = array_intersect($interfaceNamesBefore, $interfaceNamesAfter);

		foreach ($removed as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			$interfaceBefore = $interfacesBeforeKeyed[$key];

			$data = new InterfaceRemoved($fileBefore, $interfaceBefore);
			$report->addInterface($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceBefore = $interfacesBeforeKeyed[$key];
			$fileAfter = $filesAfterKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceAfter = $interfacesAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($interfaceBefore != $interfaceAfter) {
				// Check if the name of the interface has changed case.
				// If we entered this section then the normalized names (lowercase) were equal.
				if ($interfaceBefore->name !== $interfaceAfter->name) {
					$report->add(
						'interface',
						new InterfaceCaseChanged(
							$fileBefore,
							$interfaceBefore,
							$fileAfter,
							$interfaceAfter
						)
					);
				}

				$analyzer = new ClassMethodAnalyzer('interface', $fileBefore, $fileAfter);
				$interfaceMethodReport = $analyzer->analyze($interfaceBefore, $interfaceAfter);
				$report->merge($interfaceMethodReport);
			}
		}

		foreach ($added as $key) {
			$fileAfter = $filesAfterKeyed[$key];
			$interfaceAfter = $interfacesAfterKeyed[$key];

			$data = new InterfaceAdded($fileAfter, $interfaceAfter);
			$report->addInterface($data);
		}

		return $report;
	}
}
