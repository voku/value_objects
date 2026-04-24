# Changelog

### [Unreleased]

### 0.2.0 (2026-04-24)

**Breaking changes:**
- moved source namespace from `voku\value_objects` to the root namespace (e.g. `use voku\ValueObjectString`)
- added `InterfaceValueObject` interface that all value objects now implement
- renamed `ValueObjectCurrency` to `ValueObjectCurrencyCode`
- renamed `ValueObjectVat` to `ValueObjectVatPercentage`
- removed `ValueObjectAntiXss`; use `ValueObjectStringNonXss` instead

**New classes:**
- added `ValueObjectIntPositive` — integer value object that only accepts values > 0
- added `ValueObjectStringClass` — string value object restricted to valid class-name strings
- added `ValueObjectStringNonXss` — string value object with automatic XSS sanitisation (replaces `ValueObjectAntiXss`)

**Bug fixes:**
- restored `$value <= 0` guard in `ValueObjectIntPositive::validate()` (was accidentally dropped during PHPStan fixes)
- suppressed false-positive MathUtils warning in `ValueObjectNumeric::i18n_number_parse()`
- fixed all 20 PHPStan 2.x errors across `AbstractValueObject`, `ValueObjectBool`, `ValueObjectInt`, `ValueObjectIntPositive`, `ValueObjectNumeric`, `ValueObjectString`, and `MathUtils`

**Testing:**
- increased code coverage from ~79% to ~92% (+12%) by adding tests for `ValueObjectString`, `ValueObjectInt`, `ValueObjectNumeric`, `MathUtils` (comparisons, arithmetic operations, i18n helpers, anti-XSS, numberOfDecimals) and `AbstractValueObject` (equals, same, valueForDatabase)

**PHP version support:**
- added PHP 8.3, 8.4, and 8.5 to the supported version matrix in CI and `composer.json`

**Dependency updates:**
- updated `voku/portable-utf8` to `~6.1.0`
- updated `voku/stringy` to `~7.0.0` (resolves transitive conflict with `voku/anti-xss`)
- updated `voku/email-check` to its latest compatible version
- upgraded `phpstan/phpstan` to `2.1.*` (level 8) with full compatibility fixes
- updated `php-cs-fixer/shim` to `v3.95.*`

**CI / infrastructure:**
- updated GitHub Actions: `actions/checkout` → v4/v6, `actions/cache` → v5, `actions/upload-artifact` → v7, `codecov/codecov-action` → v6, `shivammathur/setup-php` → v2.37.0
- added `.deepsource.toml` for DeepSource static analysis
- migrated Renovate config to the new schema

### 0.1.0 (2023-08-09)

- init release
