<?php

namespace PHPSemVerChecker\Console\Command;

use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\Configuration\LevelMapping;
use PHPSemVerChecker\Console\InputMerger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
	/**
	 * @var \PHPSemVerChecker\Configuration\Configuration
	 */
	protected $config;

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface   $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		parent::initialize($input, $output);
		$configPath = $input->getOption('config');
		$this->config = $configPath ? Configuration::fromFile($configPath) : Configuration::defaults('php-semver-checker');
		$inputMerger = new InputMerger();
		$inputMerger->merge($input, $this->getDefinition(), $this->config);

		// Set overrides
		LevelMapping::setOverrides($this->config->getLevelMapping());
	}
}
