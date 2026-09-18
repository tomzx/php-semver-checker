<?php

namespace PHPSemVerChecker\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Renders a Markdown compatible table.
 */
class MarkdownTable
{
	/**
	 * @var array
	 */
	private $headers = [];
	/**
	 * @var array
	 */
	private $rows = [];
	/**
	 * @var \Symfony\Component\Console\Output\OutputInterface
	 */
	private $output;
	/**
	 * @var int[]
	 */
	private $columnWidths;

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	public function __construct(OutputInterface $output)
	{
		$this->output = $output;
	}

	/**
	 * Set the column headers.
	 *
	 * @param array $headers
	 * @return $this
	 */
	public function setHeaders(array $headers)
	{
		// Ensure zero-indexed array
		$this->headers = array_values($headers);
		return $this;
	}

	/**
	 * Sets all rows, replacing any existing.
	 *
	 * @param array $rows
	 * @return $this
	 */
	public function setRows(array $rows)
	{
		$this->rows = [];
		return $this->addRows($rows);
	}

	/**
	 * @param array $rows
	 * @return $this
	 */
	public function addRows(array $rows)
	{
		foreach ($rows as $row) {
			$this->addRow($row);
		}
		return $this;
	}

	/**
	 * @param \Symfony\Component\Console\Helper\TableSeparator|array $row
	 * @return $this
	 */
	public function addRow($row)
	{
		if ($row instanceof TableSeparator) {
			$this->rows[] = $row;

			return $this;
		}
		if (!is_array($row)) {
			throw new \InvalidArgumentException('A row must be an array or a TableSeparator instance.');
		}
		$this->rows[] = array_values($row);
		return $this;
	}

	/**
	 * @param int   $index
	 * @param array $row
	 * @return $this
	 */
	public function setRow($index, array $row)
	{
		$this->rows[$index] = $row;
		return $this;
	}

	/**
	 * Renders table to output.
	 */
	public function render()
	{
		$this->prepare();
		$this->output->writeln('');
		$this->renderRow($this->headers);
		$this->renderRowSeparator();
		foreach ($this->rows as $row) {
			$this->renderRow($row);
		}
	}

	/**
	 * Renders a single row.
	 *
	 * @param \Symfony\Component\Console\Helper\TableSeparator|array $row
	 */
	private function renderRow($row)
	{
		if ($row instanceof TableSeparator) {
			$this->renderRowSeparator();
			return;
		}
		$this->output->write('| ');
		$cells = [];
		foreach ($row as $index => $content) {
			$cell = $content;
			$padding = $this->columnWidths[$index] - Helper::strlenWithoutDecoration($this->output->getFormatter(), $content);
			$cell .= str_repeat(' ', $padding);
			$cells[] = $cell;
		}
		$this->output->writeln(implode(' | ', $cells) . ' |');
	}

	/**
	 * Renders the row separator. In this case it should only be used as a header separator.
	 */
	private function renderRowSeparator()
	{
		$this->output->write('|');
		foreach ($this->columnWidths as $columnWidth) {
			$this->output->write(str_repeat('-',  $columnWidth + 1));
			$this->output->write('-|');
		}
		$this->output->writeln('');
	}

	/**
	 * Prepare for rendering.
	 */
	private function prepare()
	{
		$this->columnWidths = [];
		$this->prepareColumnWidths($this->headers);
		foreach ($this->rows as $row) {
			$this->prepareColumnWidths($row);
		}
	}

	/**
	 * Extracts maximum column widths from a row.
	 *
	 * @param \Symfony\Component\Console\Helper\TableSeparator|array $row
	 */
	private function prepareColumnWidths($row)
	{
		if ($row instanceof TableSeparator) {
			return;
		}
		foreach ($row as $index => $content) {
			$currentMaximum = 0;
			if (isset($this->columnWidths[$index])) {
				$currentMaximum = $this->columnWidths[$index];
			}
			$width = Helper::strlenWithoutDecoration($this->output->getFormatter(), $content);
			$this->columnWidths[$index] = max($currentMaximum, $width);
		}
	}
}
