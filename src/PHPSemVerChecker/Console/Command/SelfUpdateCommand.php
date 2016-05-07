<?php

namespace PHPSemVerChecker\Console\Command;

use Herrera\Json\Exception\FileException;
use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends Command {
	/**
	 * @var string
	 */
	const MANIFEST_FILE = 'https://tomzx.github.io/php-semver-checker/manifest.json';

	/**
	 * @return void
	 */
	protected function configure()
	{
		$this
			->setName('self-update')
			->setDescription('Update php-semver-checker.phar to the latest version')
		;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface   $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
		$manager->update($this->getApplication()->getVersion());
	}
}
