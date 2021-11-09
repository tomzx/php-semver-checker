<?php

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Console\Command\CompareCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{

	const VERSION = '@package_version@';

	/**
	 * @var string
	 */
	private static $logo = '    ____  ______   _______
   / __ \/ ___/ | / / ___/
  / /_/ /__  /| |/ / /__
 / .___/____/ |___/\___/
/_/
';

	public function __construct()
	{
		parent::__construct('PHP Semantic Versioning Checker by Tom Rochette', self::VERSION);

		// Suppress deprecated warnings
		error_reporting(E_ALL & ~E_DEPRECATED);
	}

	/**
	 * @return string
	 */
	public function getHelp()
	{
		return self::$logo . parent::getHelp();
	}

	/**
	 * @return array|\Symfony\Component\Console\Command\Command[]
	 */
	protected function getDefaultCommands()
	{
		$commands = parent::getDefaultCommands();
		$commands[] = $this->add(new CompareCommand());
		return $commands;
	}
}
