<?php

// PHP 7.3 Features

// Trailing commas in function calls
function sum(...$numbers) {
    return array_sum($numbers);
}

$result = sum(
    1,
    2,
    3,
    4,
    5, // Trailing comma now allowed
);

// Trailing commas in method calls
class Calculator
{
    public function add(...$numbers)
    {
        return array_sum($numbers);
    }
}

$calc = new Calculator();
$total = $calc->add(
    10,
    20,
    30, // Trailing comma
);

// is_countable function
function checkCountable($value): bool
{
    return is_countable($value);
}

// array_key_first and array_key_last
function arrayKeyFunctions(): void
{
    $array = ['a' => 1, 'b' => 2, 'c' => 3];

    $firstKey = array_key_first($array);
    $lastKey = array_key_last($array);
}

// JSON_THROW_ON_ERROR flag
function jsonWithException(string $json): array
{
    return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
}

// Flexible Heredoc and Nowdoc syntax
function heredocExample(): string
{
    $html = <<<HTML
        <div>
            <p>Indented content</p>
        </div>
        HTML;

    return $html;
}

// PCRE2 pattern support
function regexFeatures(string $text): bool
{
    return preg_match('/pattern/u', $text) === 1;
}

// Multibyte string case mapping
function mbStringFeatures(): void
{
    $text = "Hello World";
    $lower = mb_convert_case($text, MB_CASE_LOWER);
    $upper = mb_convert_case($text, MB_CASE_UPPER);
}
