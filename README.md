# php-composer-reader

A PHP library to manipulate and read the composer.json file.

## Usage

Load the Reader

```php
require 'vendor/autoload';

$reader = new ComposerReader('path/to/composer.json');

if (!$reader->canRead()) {
   throw new Exception("Unable to read json.");
}

if (!$reader->canWrite()) {
   throw new Exception("Unable to write to existing json.");
}

// dump full content
var_dump($reader->getContent());
```

Get require section data

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new RequireSection($reader);

foreach($section as $package) {
    echo $package->name . ' with ' . $package->constriant;
}
```

Get autoloading section data (psr0 and psr4 are merged together)

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new AutoloadSection($reader);

foreach ($section as $autoload) {
    echo $autoload->namespace . ' with ' . $autoload->source;
}
```