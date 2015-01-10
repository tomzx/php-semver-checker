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
- Classes
	- Class added -> **MINOR**
	- Class removed -> **MAJOR**
	- Class method added -> **MINOR**
	- Class method removed -> **MAJOR**
	- Class method parameter mismatch -> **MAJOR**

## Example

```bash
php bin/php-semver-checker compare --before-source before/ --after-source after/


```

## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.