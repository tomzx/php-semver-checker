<?php

namespace PHPSemVerChecker\Wrapper;

class Filesystem
{
	/**
	 * @param string $filename
	 * @param mixed  $data
	 * @return bool|int
	 */
	public function write($filename, $data)
	{
		return file_put_contents($filename, $data);
	}

	/**
	 * @param string $filename
	 * @return bool|string
	 */
	public function read($filename)
	{
		return file_get_contents($filename);
	}
}
