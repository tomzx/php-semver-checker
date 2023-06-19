# Changelog

This project follows [Semantic Versioning 2.0.0](http://semver.org/).

## <a name="unreleased"></a>Unreleased

## <a name="v0.16.0"></a>v0.16.0 (2023-06-18)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.15.1...v0.16.0)
### Changed
* Added strict typing
* Bumped minimum PHP version to ^8.0
* Added support for union type comparison
* Bumped symfony/console to ^6.0
* Bumped symfony/yaml to ^6.0

## <a name="v0.15.1"></a>v0.15.1 (2021-12-30)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.15.0...v0.15.1)
### Changed
* Bumped hassankhan/config to ^3.0.0

### Fixed
* Apply typing to `Report` class

### Removed
* Deprecation warnings suppression

## <a name="v0.15.0"></a>v0.15.0 (2021-11-07)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.14.0...v0.15.0)
### Added
* Add support for PHP 8.0, 8.1
* Use of GitHub actions for CI

### Changed
* PHP minimum version from >=7.2.29 to >=7.3

### Removed
* Drop support for PHP 7.2
* Use of travis-ci for CI

## <a name="v0.14.0"></a>v0.14.0 (2020-04-17)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.13.0...v0.14.0)
### Added
* [#100] Add support for PHP 7.4

### Removed
* Drop support for PHP 5.6, 7.0, 7.1 and hhvm

## <a name="v0.13.0"></a>v0.13.0 (2019-04-19)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.12.1...v0.13.0)
### Added
* [#99] Add support for class [V154], class method [V150, V156, V157, V151, V152, V158, V159], function [V160], interface [V153] and trait [V155] case change

### Changed
* Update V059 level from PATCH to MAJOR

### Fixed
* [#94] PHP 7.1 nullable types not supported

## <a name="v0.12.1"></a>v0.12.1 (2018-02-08)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.12.0...v0.12.1)
### Changed
* Remove dependency to herrera-io/phar-update in box.json

## <a name="v0.12.0"></a>v0.12.0 (2018-02-08)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.11.0...v0.12.0)
### Changed
* Update composer outdated dependencies

### Removed
* Remove the self-update command

## <a name="v0.11.0"></a>v0.11.0 (2017-12-09)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.10.0...v0.11.0)
### Added
* Add support for PHP 7.0/7.1/7.2 during parsing of files

### Changed
* Update nikic/php-parser from ~2.0 to ^3.1
* Update all dependencies to use the caret instead of the tilde

## <a name="v0.10.0"></a>v0.10.0 (2016-05-13)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.9.1...v0.10.0)
### Added
* Add support to check for function, class/interface/trait methods signature:
	* Parameter added/removed
	* Parameter typing added/removed
	* Parameter default added/removed
	* Parameter default value changed

### Changed
* [#83] Removing method default parameter value should generate MAJOR level entry
* Update V018, V021 level from PATCH to MINOR
* Update V059 level from MAJOR to PATCH

## <a name="v0.9.1"></a>v0.9.1 (2016-02-11)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.9.0...v0.9.1)
### Changed
* Update dependency hassankhan/config from ~0.9 to ~0.10 to support .dist extensions.

## <a name="v0.9.0"></a>v0.9.0 (2016-01-27)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.8.1...v0.9.0)
### Changed
* Update dependency tomzx/finder from ~0.1@dev to ~0.1.
* Update dependencies symfony/console and symfony/yaml to support ~3.0.
* Update dependency hassankhan/config from ~0.8 to ~0.9 to support symfony/yaml ~3.0.
* [#79] Update nikic/php-parser from ~1.3 to ~2.0 to support PHP 7

## <a name="v0.8.1"></a>v0.8.1 (2016-01-23)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.8.0...v0.8.1)
### Fixed
* [#75] InputMerger not merging options with defaults properly

## <a name="v0.8.0"></a>v0.8.0 (2016-01-23)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.7.0...v0.8.0)
### Added
* `php-semver-checker` can now be called from `vendor/bin/php-semver-checker`
*  Add ProgressScanner to manage progress of scan jobs (thanks to @nochso)
* [#67] Support CLI parameters in the configuration file (thanks to @nochso)

## <a name="v0.7.0"></a>v0.7.0 (2015-06-25)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.6.3...v0.7.0)
### Added
* Support for configuration in php, ini, xml, json and yaml through hassankhan/config

### Changed
* Update nikic/php-parser dependency to use ~1.3

## <a name="v0.6.3"></a>v0.6.3 (2015-06-18)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.6.2...v0.6.3)
### Changed
* Depend on symfony/console ~2.7 instead of 2.7.*@dev since it is now Symfony's LTS
* Update V018 and V021 level from MINOR to PATCH
* Update V059 level from PATCH to MAJOR

### Fixed
* [#62] PHP Notice for an Unknown operation

## <a name="v0.6.2"></a>v0.6.2 (2015-05-19)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.6.1...v0.6.2)
### Changed
* [#60] [V032] Adding an interface is now a MINOR increment (was a MAJOR increment)

## <a name="v0.6.1"></a>v0.6.1 (2015-05-03)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.6.0...v0.6.1)
### Changed
* Use : instead of # to allow IDE's such as PHPStorm to link to the line of code

### Fixed
* [#56] Reporter will output a path/location for files that were removed

## <a name="v0.6.0"></a>v0.6.0 (2015-05-03)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.5.0...v0.6.0)
### Changed
* [#23] source-before/source-after arguments are now optional through --include-before/--include-after
* [#45] Replaced File_Iterator with Symfony Finder

## <a name="v0.5.0"></a>v0.5.0 (2015-05-02)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.4.1...v0.5.0)
### Added
* [#3] Make operation impact level configurable
* [#25] Display property visibility in compare report
* [#26] Display method visibility in compare report
* [#32] Generate output in JSON
* Added support for self-updating the .phar file

### Fixed
* Function target would output namespace\function::function instead of namespace\function

## <a name="v0.4.1"></a>v0.4.1 (2015-01-15)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.4.0...v0.4.1)
### Removed
* Pre-processing progress message

## <a name="v0.4.0"></a>v0.4.0 (2015-01-15)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.3.0...v0.4.0)
### Added
* Source filtering, which greatly improves scanning a large code repository

## <a name="v0.3.0"></a>v0.3.0 (2015-01-14)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.2.0...v0.3.0)
### Added
* [#5] Semantic versioning on class/trait properties

### Changed
* Improvement to the `box.json` configuration to enable compactors and compression

## <a name="v0.2.0"></a>v0.2.0 (2015-01-14)
[Full Changelog](https://github.com/tomzx/php-semver-checker/compare/v0.1.0...v0.2.0)
### Added
* [#17] Initial implementation of codes to identify verification rules
* Support for building `phar`

#### Rules
* [#7] Adding private class/trait methods should generated a PATCH level
* Adding/Removing traits public/protected method should generate a MAJOR level
* Adding public/protected class methods should generate a MAJOR level.
* [#10] Adding methods to an interface should generate a MAJOR

## <a name="v0.1.0"></a>v0.1.0 (2015-01-11)

Initial release
