<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Registry;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;

class Registry
{
	/**
	 * A list of contexts with all the nodes that were found in the source code.
	 *
	 * @var array
	 */
	public $data; // TODO: Convert to protected <tom@tomrochette.com>
	/**
	 * A list of contexts with all the file path leading
	 *
	 * @var array
	 */
	public $mapping; // TODO: Convert to protected <tom@tomrochette.com>
	/**
	 *
	 *
	 * @var string
	 */
	protected $currentFile;

	public function __construct()
	{
		$contexts = [
			'class'     => [],
			'function'  => [],
			'interface' => [],
			'trait'     => [],
		];

		$this->data = $contexts;
		$this->mapping = $contexts;
	}

	/**
	 * @param \PhpParser\Node\Stmt\Class_ $class
	 */
	public function addClass(Class_ $class)
	{
		$this->addNode('class', $class);
	}

	/**
	 * @param \PhpParser\Node\Stmt\Function_ $function
	 */
	public function addFunction(Function_ $function)
	{
		$this->addNode('function', $function);
	}

	/**
	 * @param \PhpParser\Node\Stmt\Interface_ $interface
	 */
	public function addInterface(Interface_ $interface)
	{
		$this->addNode('interface', $interface);
	}

	/**
	 * @param \PhpParser\Node\Stmt\Trait_ $trait
	 */
	public function addTrait(Trait_ $trait)
	{
		$this->addNode('trait', $trait);
	}

	/**
	 * @param string $context
	 * @param Stmt   $node
	 */
	protected function addNode(string $context, Stmt $node)
	{
		$fullyQualifiedName = $this->fullyQualifiedName($node);
		$this->data[$context][$fullyQualifiedName] = $node;
		$this->mapping[$context][$fullyQualifiedName] = $this->getCurrentFile();
	}

	/**
	 * @param Stmt $node
	 * @return string
	 */
	protected function fullyQualifiedName(Stmt $node): string
	{
		return isset($node->namespacedName) ? $node->namespacedName->toString() : $node->name->toString();
	}

	/**
	 * @param string $file
	 */
	public function setCurrentFile(string $file)
	{
		$this->currentFile = realpath($file);
	}

	/**
	 * @return string|null
	 */
	public function getCurrentFile(): ?string
	{
		return $this->currentFile;
	}
}
