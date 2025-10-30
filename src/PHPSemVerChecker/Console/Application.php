<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Console\Command\CompareCommand;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;

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
	}

	/**
	 * @return string
	 */
	public function getHelp(): string
	{
		return self::$logo . parent::getHelp();
	}

	/**
	 * @return array|\Symfony\Component\Console\Command\Command[]
	 */
	protected function getDefaultCommands(): array
	{
		// When running from PHAR, manually build command list without completion command
		// as it tries to use DirectoryIterator which doesn't work in PHAR context
		if (\Phar::running()) {
			$commands = [new HelpCommand(), new ListCommand()];
		} else {
			$commands = parent::getDefaultCommands();
		}

		$commands[] = $this->add(new CompareCommand());
		return $commands;
	}
}
