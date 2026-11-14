<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Finder;

use Finder\Adapter\SymfonyFinder as BaseFinder;

class Finder
{
	/**
	 * @param string $path
	 * @param array  $includes
	 * @param array  $excludes
	 * @return array
	 */
	public function find(string $path, array $includes, array $excludes = []): array
	{
		$finder = new BaseFinder();
		$finder->ignoreDotFiles(true)
			->files()
			->name('*.php')
			->in($path);

		foreach ($includes as $include) {
			$finder->path($include);
		}

		foreach ($excludes as $exclude) {
			$finder->notPath($exclude);
		}

		$files = [];
		foreach ($finder as $file) {
			$files[] = $file->getRealpath();
		}

		return $files;
	}

	/**
	 * @param string      $path
	 * @param string|null $includes
	 * @param string|null $excludes
	 * @return array
	 */
	public function findFromString(string $path, ?string $includes, ?string $excludes): array
	{
		if ($includes === '*' || $includes === null) {
			$includes = [];
		} else {
			$includes = preg_split('@(?:\s*,\s*|^\s*|\s*$)@', $includes, -1, PREG_SPLIT_NO_EMPTY);
		}

		if ($excludes === '*' || $excludes === null) {
			$excludes = [];
		} else {
			$excludes = preg_split('@(?:\s*,\s*|^\s*|\s*$)@', $excludes, -1, PREG_SPLIT_NO_EMPTY);
		}

		return $this->find($path, $includes, $excludes);
	}
}
