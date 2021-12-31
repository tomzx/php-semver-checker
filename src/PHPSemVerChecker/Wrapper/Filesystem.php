<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Wrapper;

class Filesystem
{
	/**
	 * @param string $filename
	 * @param mixed  $data
	 * @return bool|int
	 */
	public function write(string $filename, $data)
	{
		return file_put_contents($filename, $data);
	}

	/**
	 * @param string $filename
	 * @return bool|string
	 */
	public function read(string $filename)
	{
		return file_get_contents($filename);
	}
}
