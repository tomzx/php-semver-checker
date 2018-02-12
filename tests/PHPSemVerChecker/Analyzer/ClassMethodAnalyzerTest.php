<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Analyzer\ClassMethodAnalyzer;
use PHPSemVerChecker\Configuration\LevelMapping;
use PHPSemVerChecker\Operation\Visibility;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class ClassMethodAnalyzerTest extends TestCase
{
	protected function getConstructorForContext($context)
	{
		return [
			'class'     => Class_::class,
			'interface' => Interface_::class,
			'trait'     => Trait_::class,
		][$context];
	}

	/**
	 * @dataProvider providerSimilar
	 */
	public function testCompareSimilarClassMethod($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report);
	}

	public function providerSimilar()
	{
		return [
			['class', 'public', null],
			['class', 'protected', null],
			['class', 'private', null],
			['interface', 'public', null],
			['trait', 'public', null],
			['trait', 'protected', null],
			['trait', 'private', null],
		];
	}

	/**
	 * @dataProvider providerAdded
	 */
	public function testClassMethodAdded($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp');

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method has been added.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerAdded()
	{
		return [
			['class', 'public', 'V015'],
			['class', 'protected', 'V016'],
			['class', 'private', 'V028'],
			['interface', 'public', 'V034'],
			['trait', 'public', 'V047'],
			['trait', 'protected', 'V048'],
			['trait', 'private', 'V057'],
		];
	}

	/**
	 * @dataProvider providerRemoved
	 */
	public function testClassMethodRemoved($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
				]),
			],
		]);

		$classAfter = new $constructor('tmp');

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method has been removed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerRemoved()
	{
		return [
			['class', 'public', 'V006'],
			['class', 'protected', 'V007'],
			['class', 'private', 'V029'],
			['interface', 'public', 'V035'],
			['trait', 'public', 'V038'],
			['trait', 'protected', 'V039'],
			['trait', 'private', 'V058'],
		];
	}

	/**
	 * @dataProvider providerSignature
	 */
	public function testSimilarClassMethodSignature($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report, $context);
	}

	public function providerSignature()
	{
		return [
			['class', 'public', null],
			['class', 'protected', null],
			['class', 'private', null],
			['interface', 'public', null],
			['trait', 'public', null],
			['trait', 'protected', null],
			['trait', 'private', null],
		];
	}

	/**
	 * @dataProvider providerParameterNameChanged
	 */
	public function testCompareSimilarClassMethodWithDifferentParameterName($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('b', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter name changed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterNameChanged()
	{
		return [
			['class', 'public', 'V060'],
			['class', 'protected', 'V061'],
			['class', 'private', 'V062'],
			['interface', 'public', 'V063'],
			['trait', 'public', 'V064'],
			['trait', 'protected', 'V065'],
			['trait', 'private', 'V066'],
		];
	}

	/**
	 * @dataProvider providerParameterAdded
	 */
	public function testCompareSimilarClassMethodWithParameterAdded($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter added.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterAdded()
	{
		return [
			['class', 'public', 'V010'],
			['class', 'protected', 'V011'],
			['class', 'private', 'V031'],
			['interface', 'public', 'V036'],
			['trait', 'public', 'V042'],
			['trait', 'protected', 'V043'],
			['trait', 'private', 'V059'],
		];
	}

	/**
	 * @dataProvider providerParameterRemoved
	 */
	public function testCompareSimilarClassMethodWithParameterRemoved($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter removed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterRemoved()
	{
		return [
			['class', 'public', 'V082'],
			['class', 'protected', 'V083'],
			['class', 'private', 'V084'],
			['interface', 'public', 'V074'],
			['trait', 'public', 'V100'],
			['trait', 'protected', 'V101'],
			['trait', 'private', 'V102'],
		];
	}

	/**
	 * @dataProvider providerParameterTypehintAdded
	 */
	public function testCompareSimilarClassMethodWithParameterTypehintAdded($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter typing added.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterTypehintAdded()
	{
		return [
			['class', 'public', 'V085'],
			['class', 'protected', 'V086'],
			['class', 'private', 'V087'],
			['interface', 'public', 'V075'],
			['trait', 'public', 'V103'],
			['trait', 'protected', 'V104'],
			['trait', 'private', 'V105'],
		];
	}

	/**
	 * @dataProvider providerParameterTypehintRemoved
	 */
	public function testCompareSimilarClassMethodWithParameterTypehintRemoved($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter typing removed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterTypehintRemoved()
	{
		return [
			['class', 'public', 'V088'],
			['class', 'protected', 'V089'],
			['class', 'private', 'V090'],
			['interface', 'public', 'V076'],
			['trait', 'public', 'V106'],
			['trait', 'protected', 'V107'],
			['trait', 'private', 'V108'],
		];
	}

	/**
	 * @dataProvider providerParameterDefaultAdded
	 */
	public function testCompareSimilarClassMethodWithDefaultParameterAdded($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', new String_('someDefaultValue'), 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter default added.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterDefaultAdded()
	{
		return [
			['class', 'public', 'V091'],
			['class', 'protected', 'V092'],
			['class', 'private', 'V093'],
			['interface', 'public', 'V077'],
			['trait', 'public', 'V109'],
			['trait', 'protected', 'V110'],
			['trait', 'private', 'V111'],
		];
	}

	/**
	 * @dataProvider providerParameterDefaultRemoved
	 */
	public function testCompareSimilarClassMethodWithDefaultParameterRemoved($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', new String_('someDefaultValue'), 'B'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter default removed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterDefaultRemoved()
	{
		return [
			['class', 'public', 'V094'],
			['class', 'protected', 'V095'],
			['class', 'private', 'V096'],
			['interface', 'public', 'V078'],
			['trait', 'public', 'V112'],
			['trait', 'protected', 'V113'],
			['trait', 'private', 'V114'],
		];
	}

	/**
	 * @dataProvider providerParameterDefaultValueChanged
	 */
	public function testCompareSimilarClassMethodWithDefaultParameterValueChanged($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', new String_('someDefaultValue'), 'B'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', new String_('someNewDefaultValue'), 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method parameter default value changed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function providerParameterDefaultValueChanged()
	{
		return [
			['class', 'public', 'V097'],
			['class', 'protected', 'V098'],
			['class', 'private', 'V099'],
			['interface', 'public', 'V079'],
			['trait', 'public', 'V115'],
			['trait', 'protected', 'V116'],
			['trait', 'private', 'V117'],
		];
	}

	/**
	 * @dataProvider providerImplementation
	 */
	public function testSimilarClassMethodImplementation($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report);
	}

	public function providerImplementation()
	{
		return [
			['class', 'public', null],
			['class', 'protected', null],
			['class', 'private', null],
			['interface', 'public', null],
			['trait', 'public', null],
			['trait', 'protected', null],
			['trait', 'private', null],
		];
	}

	/**
	 * @dataProvider providerImplementationChanged
	 */
	public function testClassMethodImplementationChanged($context, $visibility, $code)
	{
		$constructor = $this->getConstructorForContext($context);
		$classBefore = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$classAfter = new $constructor('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'type'   => Visibility::getModifier($visibility),
					'stmts' => [
						new MethodCall(new Variable('test'), 'someOtherMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer($context);
		$report = $analyzer->analyze($classBefore, $classAfter);

		$expectedLevel = LevelMapping::getLevelForCode($code);
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame($code, $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame(sprintf('[%s] Method implementation changed.', $visibility), $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report[$context][$expectedLevel][0]->getTarget());
	}

    public function providerImplementationChanged()
    {
        return [
            ['class', 'public', 'V023'],
            ['class', 'protected', 'V024'],
            ['class', 'private', 'V025'],
            ['trait', 'public', 'V052'],
            ['trait', 'protected', 'V053'],
            ['trait', 'private', 'V054'],
        ];
    }

    public function testClassMethodCaseChangeIsIgnored()
    {
        $constructor = $this->getConstructorForContext('class');
        $classBefore = new $constructor('tmp', [
            'stmts' => [
                new ClassMethod('tmpMethod', [
                    'type'   => Visibility::getModifier('public'),
                    'stmts' => [
                        new MethodCall(new Variable('test'), 'someMethod'),
                    ],
                ]),
            ],
        ]);

        $classAfter = new $constructor('tmp', [
            'stmts' => [
                new ClassMethod('tmpmethod', [
                    'type'   => Visibility::getModifier('public'),
                    'stmts' => [
                        new MethodCall(new Variable('test'), 'someMethod'),
                    ],
                ]),
            ],
        ]);

        $analyzer = new ClassMethodAnalyzer('class');
        $report = $analyzer->analyze($classBefore, $classAfter);

        Assert::assertDifference($report, 'class', Level::PATCH);
    }
}
