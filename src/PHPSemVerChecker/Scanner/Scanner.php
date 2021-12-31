<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Scanner;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Visitor\ClassVisitor;
use PHPSemVerChecker\Visitor\FunctionVisitor;
use PHPSemVerChecker\Visitor\InterfaceVisitor;
use PHPSemVerChecker\Visitor\TraitVisitor;
use RuntimeException;

class Scanner
{
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
		$this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
		$this->traverser = new NodeTraverser();

		$visitors = [
			new NameResolver(),
			new ClassVisitor($this->registry),
			new InterfaceVisitor($this->registry),
			new FunctionVisitor($this->registry),
			new TraitVisitor($this->registry),
		];

		foreach ($visitors as $visitor) {
			$this->traverser->addVisitor($visitor);
		}
	}

	/**
	 * @param string $file
	 */
	public function scan(string $file)
	{
		// Set the current file used by the registry so that we can tell where the change was scanned.
		$this->registry->setCurrentFile($file);

		$code = file_get_contents($file);

		try {
			$statements = $this->parser->parse($code);
			$this->traverser->traverse($statements);
		} catch (Error $e) {
			throw new RuntimeException('Parse Error: ' . $e->getMessage() . ' in ' . $file);
		}
	}

	/**
	 * @return \PHPSemVerChecker\Registry\Registry
	 */
	public function getRegistry(): Registry
	{
		return $this->registry;
	}
}
