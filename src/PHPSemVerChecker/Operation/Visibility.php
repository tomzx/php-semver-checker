<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;

class Visibility
{
	/**
	 * @return array
	 */
	public static function getMapping(): array
	{
		return [
			Class_::MODIFIER_PUBLIC    => 0,
			Class_::MODIFIER_PROTECTED => 1,
			Class_::MODIFIER_PRIVATE   => 2,
		];
	}

	/**
	 * @param int $visibility
	 * @return int
	 */
	public static function get(int $visibility): int
	{
		$mapping = self::getMapping();
		return $mapping[$visibility];
	}

	/**
	 * @param \PhpParser\Node\Stmt $context
	 * @return int
	 */
	public static function getForContext(Stmt $context): int
	{
		if ($context->isPublic()) {
			return Class_::MODIFIER_PUBLIC;
		} elseif ($context->isProtected()) {
			return Class_::MODIFIER_PROTECTED;
		} else {
			return Class_::MODIFIER_PRIVATE;
		}
	}

	/**
	 * @param string $visibility
	 * @return int
	 */
	public static function getModifier(string $visibility): int
	{
		if ($visibility === 'public') {
			return Class_::MODIFIER_PUBLIC;
		} elseif ($visibility === 'protected') {
			return Class_::MODIFIER_PROTECTED;
		} else {
			return Class_::MODIFIER_PRIVATE;
		}
	}

	/**
	 * @param int $visibility
	 * @return string
	 */
	public static function toString(int $visibility): string
	{
		if ($visibility === Class_::MODIFIER_PUBLIC) {
			return 'public';
		} elseif ($visibility === Class_::MODIFIER_PROTECTED) {
			return 'protected';
		} else {
			return 'private';
		}
	}
}
