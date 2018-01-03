# php-composer-reader

A PHP library to manipulate and read the composer.json file.

## Usage

Load the Reader

```php
require 'vendor/autoload';

$json = new ComposerReader('path/to/composer.json');

if (!$json->canRead()) {
   throw new Exception("Unable to read json.");
}

if (!$json->canWrite()) {
   throw new Exception("Unable to write to existing json.");
}

// dump full content
```

Get section data

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new RequireSection($reader);

foreach($section as $package) {
    echo $package->name . ' with ' . $package->constriant;
}
```