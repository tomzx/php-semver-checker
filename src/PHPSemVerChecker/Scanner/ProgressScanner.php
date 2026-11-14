<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Scanner;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ProgressScanner helps run and display the progress of a scan job.
 */
class ProgressScanner
{
	/**
	 * @var string[][]
	 */
	private $fileLists = [];
	/**
	 * @var \PHPSemVerChecker\Scanner\Scanner[]
	 */
	private $scanners = [];
	/**
	 * @var \Symfony\Component\Console\Output\OutputInterface
	 */
	private $output;
	/**
	 * @var \Symfony\Component\Console\Helper\ProgressBar
	 */
	private $progressBar;

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	public function __construct(OutputInterface $output)
	{
		$this->output = $output;
	}

	/**
	 * @param string                            $name
	 * @param string[]                          $fileList
	 * @param \PHPSemVerChecker\Scanner\Scanner $scanner
	 */
	public function addJob(string $name, array $fileList, Scanner $scanner)
	{
		$this->fileLists[$name] = $fileList;
		$this->scanners[$name] = $scanner;
	}

	/**
	 * Run all registered jobs.
	 */
	public function runJobs()
	{
		foreach (array_keys($this->scanners) as $jobName) {
			$this->runJob($jobName);
		}
	}

	/**
	 * Run a single job.
	 *
	 * @param string $jobName
	 */
	public function runJob(string $jobName)
	{
		$progress = $this->getProgressBar();
		$progress->setMessage('Scanning ' . $jobName);
		$scanner = $this->scanners[$jobName];
		foreach ($this->fileLists[$jobName] as $filePath) {
			$scanner->scan($filePath);
			$progress->advance();
		}
		if ($progress->getProgress() === $progress->getMaxSteps()) {
			$progress->clear();
		}
	}

	/**
	 * @return int
	 */
	private function getFileCount(): int
	{
		return array_sum(array_map('count', $this->fileLists));
	}

	/**
	 * @return \Symfony\Component\Console\Helper\ProgressBar
	 */
	private function getProgressBar(): ProgressBar
	{
		if ($this->progressBar === null) {
			$this->progressBar = new ProgressBar($this->output, $this->getFileCount());
			$this->progressBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%");
			$this->output->writeln('');
		}
		return $this->progressBar;
	}
}
