<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Analyzer\InterfaceAnalyzer;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class InterfaceAnalyzerTest extends TestCase {
	public function testCompareSimilarInterface()
	{
		$before = new Registry();
		$after = new Registry();

		$interfaceBefore = new Interface_('tmp');
		$before->addInterface($interfaceBefore);

		$interfaceAfter = new Interface_('tmp');
		$after->addInterface($interfaceAfter);

		$analyzer = new InterfaceAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testVXXXInterfaceRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addInterface(new Interface_('tmp'));

		$analyzer = new InterfaceAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Interface was removed.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['interface'][Level::MAJOR][0]->getTarget());
	}

	public function testVXXXInterfaceAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addInterface(new Interface_('tmp'));

		$analyzer = new InterfaceAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Interface was added.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['interface'][Level::MAJOR][0]->getTarget());
	}
}
