<?php

$ruleset = file_get_contents(__DIR__.'/../docs/Ruleset.md');

$result = preg_match_all('/(?<code>V\d+) \| (?<level>[^ ]+)/', $ruleset, $matches);

if ($result) {
	array_shift($matches);
} else {
	die('Cannot find rules in Ruleset.md');
}

$rules = array_combine($matches['code'], $matches['level']);
ksort($rules);
foreach ($rules as $code => $level) {
	echo '\'' . $code . '\' => Level::' . $level . ',' . PHP_EOL;
}
