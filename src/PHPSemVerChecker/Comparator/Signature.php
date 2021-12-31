<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Comparator;

class Signature
{
	/**
	 * @param array $parametersA
	 * @param array $parametersB
	 * @return array
	 */
	public static function analyze(array $parametersA, array $parametersB): array
	{
		$changes = [
			'parameter_added'                 => false,
			'parameter_removed'               => false,
			'parameter_renamed'               => false,
			'parameter_typing_added'          => false,
			'parameter_typing_removed'        => false,
			'parameter_default_added'         => false,
			'parameter_default_removed'       => false,
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
			if ($parametersA[$i]->var->name !== $parametersB[$i]->var->name) {
				$changes['parameter_renamed'] = true;
			}

			// Type checking
			if (Type::get($parametersA[$i]->type) !== Type::get($parametersB[$i]->type)) {
				//if ($paramsA[$i]->default !== null && $paramsB[$i]->default !== null) {
				//	$changes['parameter_default_value_changed'] = true;
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
}
