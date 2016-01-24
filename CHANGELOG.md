# Changelog

This project follows [Semantic Versioning 2.0.0](http://semver.org/).

## <a name="unreleased"></a>Unreleased

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
