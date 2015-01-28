# PHP Semantic Versioning Checker

[![License](https://poser.pugx.org/tomzx/php-semver-checker/license.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Stable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/stable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/php-semver-checker/v/unstable.svg)](https://packagist.org/packages/tomzx/php-semver-checker)
[![Build Status](https://img.shields.io/travis/tomzx/php-semver-checker.svg)](https://travis-ci.org/tomzx/php-semver-checker)
[![Code Quality](https://img.shields.io/scrutinizer/g/tomzx/php-semver-checker.svg)](https://scrutinizer-ci.com/g/tomzx/php-semver-checker/code-structure)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tomzx/php-semver-checker.svg)](https://scrutinizer-ci.com/g/tomzx/php-semver-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/tomzx/php-semver-checker.svg)](https://packagist.org/packages/tomzx/php-semver-checker)

PHP Semantic Versioning Checker is a console/library which allows you to inspect a set of before and after source code.

After the inspection is completed, you are given a list of changes that have occurred between the two changesets. For each of these changes, the level of the change (MAJOR, MINOR, PATCH) will be given, as well as the location of the change (file and line number) and a reason as to why this level change is suggested.

## Getting started

As this is still an alpha package, it is not suggested to include `php-semver-checker` directly in your composer.json. There are however a couple ways to use the tool:

1. `php composer.phar create-project tomzx/php-semver-checker --stability=dev` will clone to a new php-semver-checker folder in your current working directory
2. `php composer.phar global require tomzx/php-semver-checker --stability=dev` will clone it to your global composer location, so you can use it from anywhere.
3. `git clone https://github.com/tomzx/php-semver-checker.git` and `php composer.phar install` in the newly cloned directory.

You may also download the [latest .phar build](https://github.com/tomzx/php-semver-checker/releases). Note that the .phar build is generally less bleeding edge than the previously mentioned methods.

## Current ruleset & verification codes

See [`docs/Ruleset.md`](docs/Ruleset.md) for an exhaustive list of currently supported (and to come) ruleset.

Verification codes are a mean to uniquely identify a semantic versioning trigger (a condition which upon detection, requires your code changes to be versioned).

## Example

```bash
php bin/php-semver-checker compare tests/fixtures/before tests/fixtures/after

Suggested semantic versioning change: MAJOR

Class (MAJOR)
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+
| Level | Location                                                     | Target                                                     | Reason                                     | Code |
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+
| MAJOR | tests\fixtures\before\ClassRemoved.php#                      | fixtures\ClassRemoved                                      | Class was removed.                         | V005 |
| MAJOR | tests\fixtures\after\ClassMethodAdded.php#7                  | fixtures\ClassMethodAdded::publicMethod                    | [public] Method has been added.            | V015 |
| MAJOR | tests\fixtures\after\ClassMethodAdded.php#12                 | fixtures\ClassMethodAdded::protectedMethod                 | [protected] Method has been added.         | V016 |
| MAJOR | tests\fixtures\after\ClassMethodParameterChanged.php#7       | fixtures\ClassMethodParameterChanged::publicMethod         | [public] Method parameter changed.         | V010 |
| MAJOR | tests\fixtures\after\ClassMethodParameterChanged.php#12      | fixtures\ClassMethodParameterChanged::protectedMethod      | [protected] Method parameter changed.      | V011 |
| MAJOR | tests\fixtures\before\ClassMethodRemoved.php#7               | fixtures\ClassMethodRemoved::publicMethod                  | [public] Method has been removed.          | V006 |
| MAJOR | tests\fixtures\before\ClassMethodRemoved.php#12              | fixtures\ClassMethodRemoved::protectedMethod               | [protected] Method has been removed.       | V007 |
| MAJOR | tests\fixtures\after\ClassPropertyAdded.php#7                | fixtures\ClassPropertyAdded::$a                            | [public] Property has been added.          | V019 |
| MAJOR | tests\fixtures\after\ClassPropertyAdded.php#9                | fixtures\ClassPropertyAdded::$b                            | [protected] Property has been added.       | V020 |
| MAJOR | tests\fixtures\before\ClassPropertyRemoved.php#7             | fixtures\ClassPropertyRemoved::$a                          | [public] Property has been removed.        | V008 |
| MAJOR | tests\fixtures\before\ClassPropertyRemoved.php#9             | fixtures\ClassPropertyRemoved::$b                          | [protected] Property has been removed.     | V009 |
| MINOR | tests\fixtures\after\ClassAdded.php#5                        | fixtures\ClassAdded                                        | Class was added.                           | V014 |
| PATCH | tests\fixtures\after\ClassMethodAdded.php#17                 | fixtures\ClassMethodAdded::privateMethod                   | [private] Method has been added.           | V028 |
| PATCH | tests\fixtures\after\ClassMethodImplementationChanged.php#7  | fixtures\ClassMethodImplementationChanged::publicMethod    | [public] Method implementation changed.    | V023 |
| PATCH | tests\fixtures\after\ClassMethodImplementationChanged.php#12 | fixtures\ClassMethodImplementationChanged::protectedMethod | [protected] Method implementation changed. | V024 |
| PATCH | tests\fixtures\after\ClassMethodImplementationChanged.php#17 | fixtures\ClassMethodImplementationChanged::privateMethod   | [private] Method implementation changed.   | V025 |
| PATCH | tests\fixtures\after\ClassMethodParameterChanged.php#17      | fixtures\ClassMethodParameterChanged::privateMethod        | [private] Method parameter changed.        | V031 |
| PATCH | tests\fixtures\after\ClassMethodParameterNameChanged.php#7   | fixtures\ClassMethodParameterNameChanged::publicMethod     | [public] Method parameter name changed.    | V060 |
| PATCH | tests\fixtures\after\ClassMethodParameterNameChanged.php#12  | fixtures\ClassMethodParameterNameChanged::protectedMethod  | [protected] Method parameter name changed. | V061 |
| PATCH | tests\fixtures\after\ClassMethodParameterNameChanged.php#17  | fixtures\ClassMethodParameterNameChanged::privateMethod    | [private] Method parameter name changed.   | V062 |
| PATCH | tests\fixtures\before\ClassMethodRemoved.php#17              | fixtures\ClassMethodRemoved::privateMethod                 | [private] Method has been removed.         | V029 |
| PATCH | tests\fixtures\after\ClassPropertyAdded.php#11               | fixtures\ClassPropertyAdded::$c                            | [private] Property has been added.         | V026 |
| PATCH | tests\fixtures\before\ClassPropertyRemoved.php#11            | fixtures\ClassPropertyRemoved::$c                          | [private] Property has been removed.       | V027 |
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+

Function (MAJOR)
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+
| Level | Location                                                 | Target                                                                | Reason                           | Code |
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+
| MAJOR | tests\fixtures\before\FunctionRemoved.php#5              | fixtures\functionRemoved::functionRemoved                             | Function has been removed.       | V001 |
| MAJOR | tests\fixtures\before\FunctionParameterChanged.php#5     | fixtures\functionParameterChanged::functionParameterChanged           | Function parameter changed.      | V002 |
| MINOR | tests\fixtures\after\FunctionAdded.php#5                 | fixtures\functionAdded::functionAdded                                 | Function has been added.         | V003 |
| PATCH | tests\fixtures\after\FunctionImplementationChanged.php#5 | fixtures\functionImplementationChanged::functionImplementationChanged | Function implementation changed. | V004 |
| PATCH | tests\fixtures\before\FunctionParameterNameChanged.php#5 | fixtures\functionParameterNameChanged::functionParameterNameChanged   | Function parameter name changed. | V067 |
+-------+----------------------------------------------------------+-----------------------------------------------------------------------+----------------------------------+------+

Interface (MAJOR)
+-------+---------------------------------------------------------------+------------------------------------------------------------+-----------------------------------------+------+
| Level | Location                                                      | Target                                                     | Reason                                  | Code |
+-------+---------------------------------------------------------------+------------------------------------------------------------+-----------------------------------------+------+
| MAJOR | tests\fixtures\before\InterfaceRemoved.php#5                  | fixtures\InterfaceRemoved                                  | Interface was removed.                  | V033 |
| MAJOR | tests\fixtures\after\InterfaceMethodAdded.php#7               | fixtures\InterfaceMethodAdded::newMethod                   | [public] Method has been added.         | V034 |
| MAJOR | tests\fixtures\after\InterfaceMethodParameterChaged.php#7     | fixtures\InterfaceMethodParameterChanged::newMethod        | [public] Method parameter changed.      | V036 |
| MAJOR | tests\fixtures\before\InterfaceMethodRemoved.php#7            | fixtures\InterfaceMethodRemoved::newMethod                 | [public] Method has been removed.       | V035 |
| MAJOR | tests\fixtures\after\InterfaceAdded.php#5                     | fixtures\InterfaceAdded                                    | Interface was added.                    | V032 |
| PATCH | tests\fixtures\after\InterfaceMethodParameterNameChaged.php#7 | fixtures\InterfaceMethodParameterNameChanged::publicMethod | [public] Method parameter name changed. | V063 |
+-------+---------------------------------------------------------------+------------------------------------------------------------+-----------------------------------------+------+

Trait (MAJOR)
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+
| Level | Location                                                     | Target                                                     | Reason                                     | Code |
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+
| MAJOR | tests\fixtures\before\TraitRemoved.php#5                     | fixtures\TraitRemoved                                      | Trait was removed.                         | V037 |
| MAJOR | tests\fixtures\after\TraitMethodAdded.php#7                  | fixtures\TraitMethodAdded::publicMethod                    | [public] Method has been added.            | V047 |
| MAJOR | tests\fixtures\after\TraitMethodAdded.php#12                 | fixtures\TraitMethodAdded::protectedMethod                 | [protected] Method has been added.         | V048 |
| MAJOR | tests\fixtures\after\TraitMethodAdded.php#17                 | fixtures\TraitMethodAdded::privateMethod                   | [private] Method has been added.           | V057 |
| MAJOR | tests\fixtures\after\TraitMethodParameterChanged.php#7       | fixtures\TraitMethodParameterChanged::publicMethod         | [public] Method parameter changed.         | V042 |
| MAJOR | tests\fixtures\after\TraitMethodParameterChanged.php#12      | fixtures\TraitMethodParameterChanged::protectedMethod      | [protected] Method parameter changed.      | V043 |
| MAJOR | tests\fixtures\before\TraitMethodRemoved.php#7               | fixtures\TraitMethodRemoved::publicMethod                  | [public] Method has been removed.          | V038 |
| MAJOR | tests\fixtures\before\TraitMethodRemoved.php#12              | fixtures\TraitMethodRemoved::protectedMethod               | [protected] Method has been removed.       | V039 |
| MAJOR | tests\fixtures\before\TraitMethodRemoved.php#17              | fixtures\TraitMethodRemoved::privateMethod                 | [private] Method has been removed.         | V058 |
| MAJOR | tests\fixtures\after\TraitPropertyAdded.php#7                | fixtures\TraitPropertyAdded::$a                            | [public] Property has been added.          | V049 |
| MAJOR | tests\fixtures\after\TraitPropertyAdded.php#9                | fixtures\TraitPropertyAdded::$b                            | [protected] Property has been added.       | V050 |
| MAJOR | tests\fixtures\after\TraitPropertyAdded.php#11               | fixtures\TraitPropertyAdded::$c                            | [private] Property has been added.         | V055 |
| MAJOR | tests\fixtures\before\TraitPropertyRemoved.php#7             | fixtures\TraitPropertyRemoved::$a                          | [public] Property has been removed.        | V040 |
| MAJOR | tests\fixtures\before\TraitPropertyRemoved.php#9             | fixtures\TraitPropertyRemoved::$b                          | [protected] Property has been removed.     | V041 |
| MAJOR | tests\fixtures\before\TraitPropertyRemoved.php#11            | fixtures\TraitPropertyRemoved::$c                          | [private] Property has been removed.       | V056 |
| MINOR | tests\fixtures\after\TraitAdded.php#5                        | fixtures\TraitAdded                                        | Trait was added.                           | V046 |
| PATCH | tests\fixtures\after\TraitMethodImplementationChanged.php#7  | fixtures\TraitMethodImplementationChanged::publicMethod    | [public] Method implementation changed.    | V052 |
| PATCH | tests\fixtures\after\TraitMethodImplementationChanged.php#12 | fixtures\TraitMethodImplementationChanged::protectedMethod | [protected] Method implementation changed. | V053 |
| PATCH | tests\fixtures\after\TraitMethodImplementationChanged.php#17 | fixtures\TraitMethodImplementationChanged::privateMethod   | [private] Method implementation changed.   | V054 |
| PATCH | tests\fixtures\after\TraitMethodParameterChanged.php#17      | fixtures\TraitMethodParameterChanged::privateMethod        | [private] Method parameter changed.        | V059 |
| PATCH | tests\fixtures\after\TraitMethodParameterNameChanged.php#7   | fixtures\TraitMethodParameterNameChanged::publicMethod     | [public] Method parameter name changed.    | V064 |
| PATCH | tests\fixtures\after\TraitMethodParameterNameChanged.php#12  | fixtures\TraitMethodParameterNameChanged::protectedMethod  | [protected] Method parameter name changed. | V065 |
| PATCH | tests\fixtures\after\TraitMethodParameterNameChanged.php#17  | fixtures\TraitMethodParameterNameChanged::privateMethod    | [private] Method parameter name changed.   | V066 |
+-------+--------------------------------------------------------------+------------------------------------------------------------+--------------------------------------------+------+

[Scanned files] Before: 25, After: 26, Identical: 0
Time: 0.474 seconds, Memory: 4.597 MB
```
 
## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.
