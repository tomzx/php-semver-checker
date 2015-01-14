<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\InterfaceAdded;
use PHPSemVerChecker\Operation\InterfaceRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;

class InterfaceAnalyzer
{
	protected $context = 'interface';

	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$keysBefore = array_keys($registryBefore->data['interface']);
		$keysAfter = array_keys($registryAfter->data['interface']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_intersect($keysBefore, $keysAfter);

		foreach ($removed as $key) {
			$fileBefore = $registryBefore->mapping['interface'][$key];
			$interfaceBefore = $registryBefore->data['interface'][$key];

			$data = new InterfaceRemoved($fileBefore, $interfaceBefore);
			$report->addInterface($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $registryBefore->mapping['interface'][$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceBefore = $registryBefore->data['interface'][$key];
			$fileAfter = $registryAfter->mapping['interface'][$key];
			/** @var \PhpParser\Node\Stmt\Interface_ $interfaceBefore */
			$interfaceAfter = $registryAfter->data['interface'][$key];

			// Leave non-strict comparison here
			if ($interfaceBefore != $interfaceAfter) {
				$analyzer = new ClassMethodAnalyzer('interface', $fileBefore, $fileAfter);
				$interfaceMethodReport = $analyzer->analyze($interfaceBefore, $interfaceAfter);
				$report->merge($interfaceMethodReport);
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registryAfter->mapping['interface'][$key];
			$interfaceAfter = $registryAfter->data['interface'][$key];

			$data = new InterfaceAdded($fileAfter, $interfaceAfter);
			$report->addInterface($data);
		}

		return $report;
	}
}
