<?php

namespace PHPSemVerChecker\Configuration;

use PHPSemVerChecker\SemanticVersioning\Level;

class LevelMapping
{
	public static $mapping = [
		'V001' => Level::MAJOR,
		'V002' => Level::MAJOR,
		'V003' => Level::MINOR,
		'V004' => Level::PATCH,
		'V005' => Level::MAJOR,
		'V006' => Level::MAJOR,
		'V007' => Level::MAJOR,
		'V008' => Level::MAJOR,
		'V009' => Level::MAJOR,
		'V010' => Level::MAJOR,
		'V011' => Level::MAJOR,
		'V012' => Level::MAJOR,
		'V013' => Level::MAJOR,
		'V014' => Level::MINOR,
		'V015' => Level::MAJOR,
		'V016' => Level::MAJOR,
		'V017' => Level::MINOR,
		'V018' => Level::MINOR,
		'V019' => Level::MAJOR,
		'V020' => Level::MAJOR,
		'V021' => Level::MINOR,
		'V022' => Level::PATCH,
		'V023' => Level::PATCH,
		'V024' => Level::PATCH,
		'V025' => Level::PATCH,
		'V026' => Level::PATCH,
		'V027' => Level::PATCH,
		'V028' => Level::PATCH,
		'V029' => Level::PATCH,
		'V030' => Level::PATCH,
		'V031' => Level::PATCH,
		'V032' => Level::MAJOR,
		'V033' => Level::MAJOR,
		'V034' => Level::MAJOR,
		'V035' => Level::MAJOR,
		'V036' => Level::MAJOR,
		'V037' => Level::MAJOR,
		'V038' => Level::MAJOR,
		'V039' => Level::MAJOR,
		'V040' => Level::MAJOR,
		'V041' => Level::MAJOR,
		'V042' => Level::MAJOR,
		'V043' => Level::MAJOR,
		'V044' => Level::MAJOR,
		'V045' => Level::MAJOR,
		'V046' => Level::MINOR,
		'V047' => Level::MAJOR,
		'V048' => Level::MAJOR,
		'V049' => Level::MAJOR,
		'V050' => Level::MAJOR,
		'V051' => Level::MINOR,
		'V052' => Level::PATCH,
		'V053' => Level::PATCH,
		'V054' => Level::PATCH,
		'V055' => Level::MAJOR,
		'V056' => Level::MAJOR,
		'V057' => Level::MAJOR,
		'V058' => Level::MAJOR,
		'V059' => Level::PATCH,
		'V060' => Level::PATCH,
		'V061' => Level::PATCH,
		'V062' => Level::PATCH,
		'V063' => Level::PATCH,
		'V064' => Level::PATCH,
		'V065' => Level::PATCH,
		'V066' => Level::PATCH,
		'V067' => Level::PATCH,
	];

	public static function getLevelForCode($code)
	{
		return static::$mapping[$code];
	}

	public static function setOverrides(array $mapping)
	{
		foreach ($mapping as $code => $level) {
			static::$mapping[$code] = $level;
		}
	}
}
