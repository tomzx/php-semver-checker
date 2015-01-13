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
			$report->addInterface($data, Level::MAJOR);
		}

		// TODO: Verify similar interface methods <tom@tomrochette.com>

		foreach ($added as $key) {
			$fileAfter = $registryAfter->mapping['interface'][$key];
			$interfaceAfter = $registryAfter->data['interface'][$key];

			$data = new InterfaceAdded($fileAfter, $interfaceAfter);
			$report->addInterface($data, Level::MAJOR);
		}

		return $report;
	}
}
