<?php

namespace PHPSemVerChecker\Wrapper;

class Filesystem
{
	public function write($filename, $data)
	{
		file_put_contents($filename, $data);
	}

	public function read($filename)
	{
		file_get_contents($filename);
	}
}
