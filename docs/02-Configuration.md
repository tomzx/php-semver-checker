# Configuration

You may configure various options within `php-semver-checker` to make them more to your liking. In order to do so, you have to create a JSON file which will be passed to `php-semver-checker` using the `--config myconfig.json` or `-c myconfig.json` flag.

```json
{
	"level": {
		"mapping": {
			"V001": "PATCH"
		}
	}
}
```

## Configuration sections

The following describes every section of the configuration in more details in order to allow you to customize `php-semver-checker` to your liking.

### Level

#### Mapping

Every item in the mapping section presents a rule that you may override. The key is the ruleset code and the value is the semantic version level you want the rule to generate when it is detected.

```json
{
	"level": {
		"mapping": {
			"V001": "PATCH",
			"V006": "MAJOR",
		}
	}
}
```
