<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Configuration;

use PHPSemVerChecker\SemanticVersioning\Level;

class LevelMapping
{
	/**
	 * @var array
	 */
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
		'V032' => Level::MINOR,
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
		'V052' => Level::PATCH,
		'V053' => Level::PATCH,
		'V054' => Level::PATCH,
		'V055' => Level::MAJOR,
		'V056' => Level::MAJOR,
		'V057' => Level::MAJOR,
		'V058' => Level::MAJOR,
		'V059' => Level::MAJOR,
		'V060' => Level::PATCH,
		'V061' => Level::PATCH,
		'V062' => Level::PATCH,
		'V063' => Level::PATCH,
		'V064' => Level::PATCH,
		'V065' => Level::PATCH,
		'V066' => Level::PATCH,
		'V067' => Level::PATCH,
		'V068' => Level::MAJOR,
		'V069' => Level::MAJOR,
		'V070' => Level::MINOR,
		'V071' => Level::MINOR,
		'V072' => Level::MAJOR,
		'V073' => Level::MINOR,
		'V074' => Level::MAJOR,
		'V075' => Level::MAJOR,
		'V076' => Level::MAJOR,
		'V077' => Level::MINOR,
		'V078' => Level::MAJOR,
		'V079' => Level::MAJOR,
		'V082' => Level::MAJOR,
		'V083' => Level::MAJOR,
		'V084' => Level::PATCH,
		'V085' => Level::MAJOR,
		'V086' => Level::MAJOR,
		'V087' => Level::PATCH,
		'V088' => Level::MAJOR,
		'V089' => Level::MAJOR,
		'V090' => Level::PATCH,
		'V091' => Level::MINOR,
		'V092' => Level::MINOR,
		'V093' => Level::PATCH,
		'V094' => Level::MAJOR,
		'V095' => Level::MAJOR,
		'V096' => Level::PATCH,
		'V097' => Level::MAJOR,
		'V098' => Level::MAJOR,
		'V099' => Level::PATCH,
		'V100' => Level::MAJOR,
		'V101' => Level::MAJOR,
		'V102' => Level::MAJOR,
		'V103' => Level::MAJOR,
		'V104' => Level::MAJOR,
		'V105' => Level::MAJOR,
		'V106' => Level::MAJOR,
		'V107' => Level::MAJOR,
		'V108' => Level::MAJOR,
		'V109' => Level::MINOR,
		'V110' => Level::MINOR,
		'V111' => Level::MINOR,
		'V112' => Level::MAJOR,
		'V113' => Level::MAJOR,
		'V114' => Level::MAJOR,
		'V115' => Level::MAJOR,
		'V116' => Level::MAJOR,
		'V117' => Level::MAJOR,
		'V150' => Level::PATCH,
		'V151' => Level::PATCH,
		'V152' => Level::PATCH,
		'V153' => Level::PATCH,
		'V154' => Level::PATCH,
		'V155' => Level::PATCH,
		'V156' => Level::PATCH,
		'V157' => Level::PATCH,
		'V158' => Level::PATCH,
		'V159' => Level::PATCH,
		'V160' => Level::PATCH,
	];

	/**
	 * @param string $code
	 * @return int
	 */
	public static function getLevelForCode(string $code): int
	{
		return static::$mapping[$code];
	}

	/**
	 * @param array $mapping
	 */
	public static function setOverrides(array $mapping)
	{
		foreach ($mapping as $code => $level) {
			static::$mapping[$code] = $level;
		}
	}
}
