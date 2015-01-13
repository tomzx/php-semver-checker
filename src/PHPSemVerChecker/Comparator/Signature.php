<?php

namespace PHPSemVerChecker\Comparator;

class Signature
{
	/**
	 * @param array $paramsA
	 * @param array $paramsB
	 * @return bool
	 */
	public static function isSameTypehints(array $paramsA, array $paramsB)
	{
		$iterations = min(count($paramsA), count($paramsB));
		for ($i = 0; $i < $iterations; ++$i) {
			// TODO: Allow for contravariance <tom@tomrochette.com>
			if ( ! Type::isSame($paramsA[$i]->type, $paramsB[$i]->type)) {
				return false;
			}
		}
		// Only one of these will return its remaining values, the other returning an empty array
		$toCheck = array_slice($paramsA, $iterations) + array_slice($paramsB, $iterations);
		// If any additional argument does not have a default value, the signature has changed
		foreach ($toCheck as $param) {
			if ($param->default === null) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param array $paramsA
	 * @param array $paramsB
	 * @return bool
	 */
	public static function isSameVariables(array $paramsA, array $paramsB)
	{
		if (count($paramsA) !== count($paramsB)) {
			return false;
		}

		$iterations = min(count($paramsA), count($paramsB));
		for ($i = 0; $i < $iterations; ++$i) {
			if ( $paramsA[$i]->name != $paramsB[$i]->name) {
				return false;
			}
		}
		return true;
	}
}
