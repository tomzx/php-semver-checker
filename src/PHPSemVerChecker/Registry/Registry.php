<?php

namespace PHPSemVerChecker\Registry;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;

class Registry {
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
			'class'    => [],
			'function' => [],
			'interface' => [],
			'trait' => [],
		];

		$this->data = $contexts;
		$this->mapping = $contexts;
	}

	// TODO: Try to reduce the amount of data moved around (we don't need statements inside methods/functions) <tom@tomrochette.com>
	/**
	 * @param \PhpParser\Node\Stmt\Class_ $item
	 */
	public function addClass(Class_ $class)
	{
		$this->addNode('class', $class);
	}

	/**
	 * @param \PhpParser\Node\Stmt\Function_ $item
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
	 * @param string               $context
	 * @param \PhpParser\Node\Stmt $node
	 */
	protected function addNode($context, Node\Stmt $node)
	{
		$fullyQualifiedName = $this->fullyQualifiedName($node);
		$this->data[$context][$fullyQualifiedName] = $node;
		$this->mapping[$context][$fullyQualifiedName] = $this->getCurrentFile();
	}

	/**
	 * @param \PhpParser\Node\Stmt $node
	 * @return string
	 */
	protected function fullyQualifiedName(Node\Stmt $node)
	{
		return $node->namespacedName ? $node->namespacedName->toString() : $node->name;
	}

	/**
	 * @param string $file
	 */
	public function setCurrentFile($file)
	{
		$this->currentFile = realpath($file);
	}

	/**
	 * @return string
	 */
	public function getCurrentFile()
	{
		return $this->currentFile;
	}
}
