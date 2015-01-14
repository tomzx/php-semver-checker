# PHP Semantic Versioning Checker

[![Build Status](https://travis-ci.org/tomzx/php-semver-checker.svg)](https://travis-ci.org/tomzx/php-semver-checker)
[![Total Downloads](https://poser.pugx.org/tomzx/php-semver-checker/downloads.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Stable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/stable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/unstable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![License](https://poser.pugx.org/tomzx/php-semver-checker/license.svg)](https://packagist.org/packages/tomzx/php-semver-checker)

PHP Semantic Versioning Checker is a console/library which allows you to inspect a set of before and after source code.

After the inspection is completed, you are given a list of changes that have occurred between the two changesets. For each of these changes, the level of the change (MAJOR, MINOR, PATCH, NONE) will be given, as well as the location of the change (file and line number) and a reason as to why this level change is suggested.

## Getting started

As this is still an alpha package, it is not suggested to include `php-semver-checker` directly in your composer.phar. There are however a couple ways to use the tool:

1. `php composer.phar create-project tomzx/php-semver-checker --stability=dev` will clone to a new php-semver-checker folder in your current working directory
2. `php composer.phar global require tomzx/php-semver-checker --stability=dev` will clone it to your global composer location, so you can use it from anywhere.
3. `git clone https://github.com/tomzx/php-semver-checker.git` and `php composer.phar install` in the newly cloned directory.

As the package gets more stable, other means of distribution may become available (such as a .phar).

## Current ruleset & verification codes

See `docs/Ruleset.md` for an exhaustive list of currently supported (and to come) ruleset.

Verification codes are a mean to uniquely identify a semantic versioning trigger (a condition which upon detection, requires your code changes to be versioned).

## Example

```bash
php bin/php-semver-checker compare tests/fixtures/before tests/fixtures/after

Suggested semantic versioning change: MAJOR

Class (MAJOR)
+-------+-----------------------------------------+-----------------------+--------------------+------+
| Level | Location                                | Target                | Reason             | Code |
+-------+-----------------------------------------+-----------------------+--------------------+------+
| MAJOR | tests\fixtures\before\ClassRemoved.php# | fixtures\ClassRemoved | Class was removed. | V005 |
| MINOR | tests\fixtures\after\ClassAdded.php#5   | fixtures\ClassAdded   | Class was added.   | V014 |
+-------+-----------------------------------------+-----------------------+--------------------+------+

Function (MAJOR)
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+
| Level | Location                                                 | Target                                                                | Reason                           | Code |
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+
| MAJOR | tests\fixtures\before\FunctionRemoved.php#5              | fixtures\functionRemoved::functionRemoved                             | Function has been removed.       | V001 |
| MAJOR | tests\fixtures\before\FunctionParameterMismatch.php#5    | fixtures\functionParameterMismatch::functionParameterMismatch         | Function parameter changed.      | V002 |
| MINOR | tests\fixtures\after\FunctionAdded.php#5                 | fixtures\functionAdded::functionAdded                                 | Function has been added.         | V003 |
| PATCH | tests\fixtures\after\FunctionImplementationChanged.php#5 | fixtures\functionImplementationChanged::functionImplementationChanged | Function implementation changed. | V004 |
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+

Interface (MAJOR)
+-------+----------------------------------------------+---------------------------+------------------------+------+
| Level | Location                                     | Target                    | Reason                 | Code |
+-------+----------------------------------------------+---------------------------+------------------------+------+
| MAJOR | tests\fixtures\before\InterfaceRemoved.php#5 | fixtures\InterfaceRemoved | Interface was removed. | V033 |
| MAJOR | tests\fixtures\after\InterfaceAdded.php#5    | fixtures\InterfaceAdded   | Interface was added.   | V032 |
+-------+----------------------------------------------+---------------------------+------------------------+------+

Method (MAJOR)
+-------+-------------------------------------------------------------+------------------------------------------------------+--------------------------------+------+
| Level | Location                                                    | Target                                               | Reason                         | Code |
+-------+-------------------------------------------------------------+------------------------------------------------------+--------------------------------+------+
| MAJOR | tests\fixtures\after\ClassMethodParameterChanged.php#7      | fixtures\ClassMethodParameterMismatch::newMethod     | Method parameter changed.      | V010 |
| MAJOR | tests\fixtures\before\ClassMethodRemoved.php#7              | fixtures\ClassMethodRemoved::newMethod               | Method has been removed.       | V006 |
| MAJOR | tests\fixtures\after\InterfaceMethodParameterMismatch.php#7 | fixtures\InterfaceMethodParameterMismatch::newMethod | Method parameter changed.      | V036 |
| MAJOR | tests\fixtures\before\InterfaceMethodRemoved.php#7          | fixtures\InterfaceMethodRemoved::newMethod           | Method has been removed.       | V035 |
| MAJOR | tests\fixtures\after\TraitMethodParameterChanged.php#7      | fixtures\TraitMethodParameterMismatch::newMethod     | Method parameter changed.      | V042 |
| MAJOR | tests\fixtures\before\TraitMethodRemoved.php#7              | fixtures\TraitMethodRemoved::newMethod               | Method has been removed.       | V038 |
| MINOR | tests\fixtures\after\ClassMethodAdded.php#7                 | fixtures\ClassMethodAdded::newMethod                 | Method has been added.         | V015 |
| MINOR | tests\fixtures\after\InterfaceMethodAdded.php#7             | fixtures\InterfaceMethodAdded::newMethod             | Method has been added.         | V034 |
| MINOR | tests\fixtures\after\TraitAddedRemoved.php#7                | fixtures\TraitMethodAdded::newMethod                 | Method has been added.         | V047 |
| PATCH | tests\fixtures\after\ClassMethodImplementationChanged.php#7 | fixtures\ClassMethodImplementationChanged::newMethod | Method implementation changed. | V023 |
| PATCH | tests\fixtures\after\ClassMethodParameterChanged.php#7      | fixtures\ClassMethodParameterMismatch::newMethod     | Method parameter changed.      | V010 |
| PATCH | tests\fixtures\after\InterfaceMethodParameterMismatch.php#7 | fixtures\InterfaceMethodParameterMismatch::newMethod | Method parameter changed.      | V036 |
| PATCH | tests\fixtures\after\TraitMethodImplementationChanged.php#7 | fixtures\TraitMethodImplementationChanged::newMethod | Method implementation changed. | V038 |
| PATCH | tests\fixtures\after\TraitMethodParameterChanged.php#7      | fixtures\TraitMethodParameterMismatch::newMethod     | Method parameter changed.      | V042 |
+-------+-------------------------------------------------------------+------------------------------------------------------+--------------------------------+------+

Trait (MAJOR)
+-------+------------------------------------------+-----------------------+--------------------+------+
| Level | Location                                 | Target                | Reason             | Code |
+-------+------------------------------------------+-----------------------+--------------------+------+
| MAJOR | tests\fixtures\before\TraitRemoved.php#5 | fixtures\TraitRemoved | Trait was removed. | V037 |
| MINOR | tests\fixtures\after\TraitAdded.php#5    | fixtures\TraitAdded   | Trait was added.   | V046 |
+-------+------------------------------------------+-----------------------+--------------------+------+

Time: 0.79 seconds, Memory: 4.28 MB
```
 
## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.
