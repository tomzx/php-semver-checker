<?php

namespace PHPSemVerChecker\Finder;

use Finder\Adapter\SymfonyFinder as BaseFinder;

class Finder
{
	/**
	 * @param string $path
	 * @param array $includes
	 * @param array $excludes
	 * @return array
	 */
	public function find($path, array $includes, array $excludes = [])
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

		$files = iterator_to_array($finder->getIterator());

		return $files;
	}

	/**
	 * @param string $path
	 * @param string $includes
	 * @param string $excludes
	 * @return array
	 */
	public function findFromString($path, $includes, $excludes)
	{
		if ($includes === '*') {
			$includes = [];
		} else {
			$includes = preg_split('@(?:\s*,\s*|^\s*|\s*$)@', $includes, NULL, PREG_SPLIT_NO_EMPTY);
		}

		if ($excludes === '*') {
			$excludes = [];
		} else {
			$excludes = preg_split('@(?:\s*,\s*|^\s*|\s*$)@', $excludes, NULL, PREG_SPLIT_NO_EMPTY);
		}

		return $this->find($path, $includes, $excludes);
	}
}
