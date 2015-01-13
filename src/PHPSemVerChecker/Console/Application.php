<?php

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Console\Command\CompareCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication {

	private static $VERSION = '0.2';

	private static $logo = '    ____  ______   _______
   / __ \/ ___/ | / / ___/
  / /_/ /__  /| |/ / /__
 / .___/____/ |___/\___/
/_/
';

	public function __construct()
	{
		parent::__construct('PHP Semantic Versioning Checker by Tom Rochette', static::$VERSION);
	}

	public function getHelp()
	{
		return self::$logo . parent::getHelp();
	}

	protected function getDefaultCommands()
	{
		$commands = parent::getDefaultCommands();
		$commands[] = $this->add(new CompareCommand());
		return $commands;
	}
}
