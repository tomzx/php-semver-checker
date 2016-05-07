<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;

class Visibility
{
	/**
	 * @return array
	 */
	public static function getMapping()
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
	public static function get($visibility)
	{
		$mapping = self::getMapping();
		return $mapping[$visibility];
	}

	/**
	 * @param \PhpParser\Node\Stmt $context
	 * @return int
	 */
	public static function getForContext(Stmt $context)
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
	public static function getModifier($visibility)
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
	public static function toString($visibility)
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
