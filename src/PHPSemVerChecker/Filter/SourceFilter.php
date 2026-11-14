<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Filter;

class SourceFilter
{
	/**
	 * @param array $filesBefore
	 * @param array $filesAfter
	 * @return int
	 */
	public function filter(array &$filesBefore, array &$filesAfter): int
	{
		$hashedBefore = [];
		foreach ($filesBefore as $fileBefore) {
			$hashedBefore[sha1(file_get_contents($fileBefore))] = $fileBefore;
		}

		$hashedAfter = [];
		foreach ($filesAfter as $fileAfter) {
			$hashedAfter[sha1(file_get_contents($fileAfter))] = $fileAfter;
		}

		$intersection = array_intersect_key($hashedBefore, $hashedAfter);
		$filesBefore = array_values(array_diff_key($hashedBefore, $intersection));
		$filesAfter = array_values(array_diff_key($hashedAfter, $intersection));
		return count($intersection);
	}
}
