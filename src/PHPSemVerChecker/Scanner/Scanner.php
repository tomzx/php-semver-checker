<?php

namespace PHPSemVerChecker\Scanner;

use PhpParser\Error;
use PhpParser\Lexer\Emulative;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Visitor\ClassVisitor;
use PHPSemVerChecker\Visitor\FunctionVisitor;

class Scanner {
	/**
	 * @var \PHPSemVerChecker\Registry\Registry
	 */
	protected $registry;
	/**
	 * @var \PhpParser\Parser
	 */
	protected $parser;
	/**
	 * @var \PhpParser\NodeTraverser
	 */
	protected $traverser;

	public function __construct()
	{
		$this->registry = new Registry();
		$this->parser = new Parser(new Emulative());
		$this->traverser = new NodeTraverser();

		$this->traverser->addVisitor(new NameResolver());
		$this->traverser->addVisitor(new ClassVisitor($this->registry));
		$this->traverser->addVisitor(new FunctionVisitor($this->registry));
	}

	/**
	 * @param string $file
	 */
	public function scan($file)
	{
		// Set the current file used by the registry so that we can tell where the change was scanned.
		$this->registry->setCurrentFile($file);

		$code = file_get_contents($file);

		try {
			$statements = $this->parser->parse($code);
			$statements = $this->traverser->traverse($statements);
		} catch (Error $e) {
			echo 'Parse Error: ', $e->getMessage();
		}
	}

	/**
	 * @return \PHPSemVerChecker\Registry\Registry
	 */
	public function getRegistry()
	{
		return $this->registry;
	}
}