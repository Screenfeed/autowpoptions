# AutoWPOptions 2.0.0

Allows to manage a set of options in WordPress.

Requires **php 5.4** and **WordPress 4.4**.

## What you will be able to do

* In a multisite environment, decide if your set of options is network-wide or site-wide,
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

Without composer:

Download the package, then:

```php
require_once '/{path-to}/autowpoptions/vendor/autoload.php';
```

## How to use

Create one class that implements `Screenfeed\AutoWPOptions\OptionDefinition\OptionDefinitionInterface`.
This class will provide the following:

### Two methods returning a prefix and an identifier

A prefix and an identifier are required to be used in hook names.

### A method returning default values

They are used when an option value is not set yet. Their type is used to decide how to cast the values.

### A method returning reset values

Reset values are used if the whole set of options does not exist yet: sometimes you may want them to be different from the default values. Default values are used for the missing reset values, meaning the method can return an empty array if you don't have anything special to do here.  
You could also use them in a "Reset Options" process for example.

### A sanitization method

It is run for each option, when getting/updating it.

### A validation method

It is run once for all options on update. It allows to edit some values, depending on others for example. It can simply return the entry if you have nothing special to do here.

### Example

How to create an option that is stored as an array in the WordPress' options table:

* The option name is `myplugin_settings`,
* The current plugin version is `2.3`,
* The option must be network-wide on a multisite install.

```php
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;
use Screenfeed\AutoWPOptions\Storage\WpOption;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

$option_name       = 'myplugin_settings';
$network_wide      = true;
$plugin_version    = '2.3';
$option_definition = new Example(); // Create your own class, defining your options set.

$options = new Options(
	new WpOption( $option_name, $network_wide ),
	new Sanitizer( $plugin_version, $option_definition )
);

$options->init();

// Sets several values at once.
$options->set(
	[
		'the_array'  => [ '2', '6', '8' ],
		'the_number' => '8',
	]
);

// Returns [ 2, 6, 8 ].
$list = $options->get( 'the_array' );
```

Take a look at [this example class](https://github.com/Screenfeed/autowpoptions/blob/main/src/OptionDefinition/Example.php) to see how to create your own class.

### Lazy loading

You can use the class `Screenfeed\AutoWPOptions\Storage\LazyStorage` to prevent triggering the `set()` method several times. It will be triggered only once, upon class instance destruction.  
This class must "wrap" the real storage class (like `WpOption`).

```php
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;
use Screenfeed\AutoWPOptions\Storage\WpOption;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

$options = new Options(
	new LazyStorage( new WpOption( 'myplugin_settings', false ) ),
	new Sanitizer( '2.3', new Example() )
);

$options->init();

$options->set(
	[
		'the_array'  => [ '2', '6', '8' ],
		'the_number' => '8',
	]
);

$options->set(
	[
		'the_text' => 'Some text.',
	]
);

$options->set(
	[
		'the_other_text' => 'Some other text.',
	]
);
```

In the previous example, `update_option()` (from `WpOption->set()`) will be triggered only once. `unset( $options );` would also trigger it.

### An "upgrade process"?

The plugin version used when instanciating `Sanitizer` is stored in the option and can be used in a future plugin release for an upgrade process.  
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
