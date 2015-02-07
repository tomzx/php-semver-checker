<?php

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Console\Command\CompareCommand;
use PHPSemVerChecker\Console\Command\SelfUpdateCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication {

	const VERSION = '@package_version@';

	private static $logo = '    ____  ______   _______
   / __ \/ ___/ | / / ___/
  / /_/ /__  /| |/ / /__
 / .___/____/ |___/\___/
/_/
';

	public function __construct()
	{
		parent::__construct('PHP Semantic Versioning Checker by Tom Rochette', self::VERSION);
	}

	public function getHelp()
	{
		return self::$logo . parent::getHelp();
	}

	protected function getDefaultCommands()
	{
		$commands = parent::getDefaultCommands();
		$commands[] = $this->add(new CompareCommand());
		$commands[] = $this->add(new SelfUpdateCommand());
		return $commands;
	}
}
