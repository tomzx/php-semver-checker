<?php

error_reporting(E_ALL);

if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
	date_default_timezone_set(@date_default_timezone_get());
}

require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/Composer/TestCase.php';
