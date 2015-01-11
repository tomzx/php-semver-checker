# PHP Semantic Versioning Checker

[![Build Status](https://travis-ci.org/tomzx/php-semver-checker.svg)](https://travis-ci.org/tomzx/php-semver-checker)
[![Total Downloads](https://poser.pugx.org/tomzx/php-semver-checker/downloads.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Stable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/stable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/unstable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![License](https://poser.pugx.org/tomzx/php-semver-checker/license.svg)](https://packagist.org/packages/tomzx/php-semver-checker)

PHP Semantic Versioning Checker is a console/library which allows you to inspect a set of before and after source code.

After the inspection is completed, you are given a list of changes that have occurred between the two changesets. For each of these changes, the level of the change (MAJOR, MINOR, PATCH, NONE) will be given, as well as the location of the change (file and line number) and a reason as to why this level change is suggested.

## Current checks & ruleset

- Functions
	- Function added -> **MINOR**
	- Function removed -> **MAJOR**
	- Function parameter mismatch -> **MAJOR**
	- Function implementation changed -> **PATCH**
- Classes
	- Class added -> **MINOR**
	- Class removed -> **MAJOR**
	- Public class method added -> **MINOR**
	- Public class method removed -> **MAJOR**
	- Public class method parameter mismatch -> **MAJOR**
	- Public class method implementation changed -> **PATCH**

## Example

```bash
php bin/php-semver-checker compare laravel-4.2.15 laravel-4.2.16

Suggested semantic versioning change: MAJOR

CLASS
LEVEL	LOCATION	REASON
MAJOR	src/Illuminate/Database/Eloquent/Model.php#2550 Illuminate/Database/Eloquent/Model::getMutatorMethod	Method has been removed.
PATCH	src/Illuminate/Database/Eloquent/Model.php#243 __construct	Method implementation changed.
PATCH	src/Illuminate/Database/Eloquent/Model.php#322 addGlobalScope	Method implementation changed.
PATCH	src/Illuminate/Database/Eloquent/Model.php#333 hasGlobalScope	Method implementation changed.
PATCH	src/Illuminate/Database/Eloquent/Model.php#344 getGlobalScope	Method implementation changed.
PATCH	src/Illuminate/Database/Eloquent/Model.php#357 getGlobalScopes	Method implementation changed.
[... cut for brievity ...]

FUNCTION
LEVEL	LOCATION	REASON
```

## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.