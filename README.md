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

## Install

You can install it globally:
```sh
$ composer global require symfony/console:~2.7@dev
$ composer global require tomzx/php-semver-checker:@dev
```

## Example

```bash
php bin/php-semver-checker compare factory-muffin-1.6.4 factory-muffin-2.0.0

Suggested semantic versioning change: MAJOR

Class
+-------+-------------------------------------------------------------------------------------------------------------------+--------------------+
| Level | Location                                                                                                          | Reason             |
+-------+-------------------------------------------------------------------------------------------------------------------+--------------------+
| MAJOR | src/Zizaco/FactoryMuff/Facade/FactoryMuff.php#8 Zizaco\FactoryMuff\Facade\FactoryMuff                             | Class was removed. |
| MAJOR | src/Zizaco/FactoryMuff/FactoryMuff.php#13 Zizaco\FactoryMuff\FactoryMuff                                          | Class was removed. |
| MAJOR | src/Zizaco/FactoryMuff/Kind.php#7 Zizaco\FactoryMuff\Kind                                                         | Class was removed. |
[...]
| MINOR | src/Exceptions/DeleteFailedException.php#17 League\FactoryMuffin\Exceptions\DeleteFailedException                 | Class was added.   |
| MINOR | src/Exceptions/DeleteMethodNotFoundException.php#17 League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException | Class was added.   |
| MINOR | src/Exceptions/DeletingFailedException.php#21 League\FactoryMuffin\Exceptions\DeletingFailedException             | Class was added.   |
[... cut for brievity ...]
+-------+-------------------------------------------------------------------------------------------------------------------+--------------------+

Function
+-------+----------+--------+
| Level | Location | Reason |
+-------+----------+--------+
```

## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.