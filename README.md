# AutoWPOptions

Allows to manage a set of options in WordPress.

Requires **php 5.4** and **WordPress 4.4**.

## What you will be able to do

* Decide if your set of options is network-wide or site-wide in a multisite environment,
* Obviously, get/set/delete values,
* Provide default and reset values,
* Cast, sanitize, and validate values automatically,
* Create an upgrade process.

## How to install

With composer:

```json
"require": {
	"screenfeed/autowpoptions": "*"
},
```

## How to use

Create one class that extends *Sanitization\AbstractSanitization*.
This class will contain the following:

### Default values

**Required**. They are used when an option has no value yet. Their type is used to decide how to cast the values.

### Reset values

**Optional**. Reset values are used if the whole set of options does not exist yet: sometimes you may want them to be different from the default values. Default values are used for the missing reset values.  
You could also use them in a "Reset Options" process for example.

### A sanitization method

**Required**. It is run for each option, when getting/updating it.

### A validation method

**Required** (but can simply return the entry if not needed). It is run once for all options on update. It allows to edit some values, depending on others for example.

### Two keywords as class properties

**Required**. They are used in hook names.

### Example

How to create an option that is stored as an array in the WordPress' options table:

* The option name is `myplugin_settings`,
* The current plugin version is `2.3`,
* The option must be network-wide on a multisite install.

```php
use Screenfeed\AutoWPOptions\Storage\WpOption;
use Screenfeed\AutoWPOptions\Options;

$option_name    = 'myplugin_settings';
$network_wide   = true;
$plugin_version = '2.3';

$options_sanitization = new MyOptionsSanitization( $plugin_version );
$options_storage      = new WpOption( $option_name, $network_wide );
$options              = new Options( $options_storage, $options_sanitization );

$foobar = $options->get( 'foobar' ); // Returns an array of positive integers.
```

The `MyOptionsSanitization` class:

* The two keywords `myplugin` and `settings` are used in hook names, like the filter `get_myplugin_settings_foobar`.

```php
use Screenfeed\AutoWPOptions\Sanitization\AbstractSanitization;

class OptionSanitization extends AbstractSanitization {

	/**
	 * Prefix used in hook names.
	 *
	 * @var string
	 */
	protected $prefix = 'myplugin';

	/**
	 * Suffix used in hook names.
	 *
	 * @var string
	 */
	protected $identifier = 'settings';

	/**
	 * The default values.
	 * These are the "zero state" values.
	 * Don't use null as value.
	 *
	 * @var array<mixed>
	 */
	protected $default_values = [
		'foobar' => [],
		'barbaz' => 0,
	];

	/**
	 * Sanitizes and validates an option value. Basic casts have been made.
	 *
	 * @param  string $key     The option key.
	 * @param  mixed  $value   The value.
	 * @param  mixed  $default The default value.
	 * @return mixed
	 */
	protected function sanitize_and_validate_value( $key, $value, $default ) {
		switch ( $key ) {
			case 'foobar':
				return is_array( $value ) ? array_unique( array_map( 'absint', $value ) ) : [];
			case 'barbaz':
				return absint( $value );
		}

		return false;
	}

	/**
	 * Validates all options before storing them. Basic sanitization and validation have been made, row by row.
	 *
	 * @param  array<mixed> $values The option value.
	 * @return array<mixed>
	 */
	protected function validate_values_on_update( array $values ) {
		if ( ! in_array( $values['barbaz'], $values['foobar'], true ) ) {
			$values['barbaz'] = $this->default_values['barbaz'];
		}
		return $values;
	}
}

```

### An "upgrade process"?

The plugin version used when instanciating `MyOptionsSanitization` is stored in the option and can be used in a future plugin release for an upgrade process.  
For example:

```php
$site_version = $options->get( 'version' );

if ( version_compare( $site_version, '1.2' ) < 0 ) {
	$options->set( [ 'barbaz', 8 ] );
}

$options->set( [ 'version', '2.5' ] );
```

### Reserved keyworks

Don't use the following keywords as option keys, they are used internally:

* cached
* version

## Extending

You may want to store your options elsewhere than the WordPress' options table, maybe in configuration file (heck, why not). This package is built in such a way that it is possible.  
To do so, you need to create a class that will replace `Screenfeed\AutoWPOptions\Storage\WpOption`, and implement `Screenfeed\AutoWPOptions\Storage\StorageInterface`.
