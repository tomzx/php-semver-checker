<?php

namespace PHPSemVerChecker\Comparator;

class Signature {
	/**
	 * @param array $parametersA
	 * @param array $parametersB
	 * @return array
	 */
	public static function analyze(array $parametersA, array $parametersB)
	{
		$changes = [
			'parameter_added' => false,
			'parameter_removed' => false,
			'parameter_renamed' => false,
			'parameter_typing_added' => false,
			'parameter_typing_removed' => false,
			'parameter_default_added' => false,
			'parameter_default_removed' => false,
			'parameter_default_value_changed' => false,
		];
		$lengthA = count($parametersA);
		$lengthB = count($parametersB);

		// TODO(tom@tomrochette.com): This is only true if newer params do not have defaults
		if ($lengthA < $lengthB) {
			$changes['parameter_added'] = true;
		} elseif ($lengthA > $lengthB) {
			$changes['parameter_removed'] = true;
		}

		$iterations = min($lengthA, $lengthB);
		for ($i = 0; $i < $iterations; ++$i) {
			// Name checking
			if ($parametersA[$i]->name !== $parametersB[$i]->name) {
				$changes['parameter_renamed'] = true;
			}

			// Type checking
			if (Type::get($parametersA[$i]->type) !== Type::get($parametersB[$i]->type)) {
//				if ($paramsA[$i]->default !== null && $paramsB[$i]->default !== null) {
//					$changes['parameter_default_value_changed'] = true;
				if ($parametersA[$i]->type !== null) {
					$changes['parameter_typing_removed'] = true;
				}
				if ($parametersB[$i]->type !== null) {
					$changes['parameter_typing_added'] = true;
				}
			}

			// Default checking
			if ($parametersA[$i]->default === null && $parametersB[$i]->default === null) {
				// Do nothing
			} elseif ($parametersA[$i]->default !== null && $parametersB[$i]->default === null) {
				$changes['parameter_default_removed'] = true;
			} elseif ($parametersA[$i]->default === null && $parametersB[$i]->default !== null) {
				$changes['parameter_default_added'] = true;
			// TODO(tom@tomrochette.com): Not all nodes have a value property
			} elseif ( ! Node::isEqual($parametersA[$i]->default, $parametersB[$i]->default)) {
				$changes['parameter_default_value_changed'] = true;
			}
		}

		return $changes;
	}

	/**
	 * @param array $paramsA
	 * @param array $paramsB
	 * @return string|bool
	 */
	public static function requiredParametersChanges(array $paramsA, array $paramsB)
	{
		$iterations = min(count($paramsA), count($paramsB));
		// Verify that all params default value match (either they're both null => parameter is required, or both
		// set to the same required value)
		for ($i = 0; $i < $iterations; ++$i) {
			if ($paramsA[$i]->default !== $paramsB[$i]->default) {
				return $paramsB[$i]->default ? 'removed' : 'added';
			}
		}

		// Only one of these will return its remaining values, the other returning an empty array
		$toCheck = array_slice($paramsA, $iterations) + array_slice($paramsB, $iterations);
		$operation = count($paramsA) < count($paramsB) ? 'added' : 'removed';
		// If any additional argument does not have a default value, the signature has changed
		foreach ($toCheck as $param) {
			if ($param->default === null) {
				return $operation;
			}
		}
		return false;
	}

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
			if ($paramsA[$i]->name != $paramsB[$i]->name) {
				return false;
			}
		}
		return true;
	}
}
