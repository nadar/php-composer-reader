# Read and manipulate the composer.json file

[![Build Status](https://travis-ci.org/nadar/php-composer-reader.svg?branch=master)](https://travis-ci.org/nadar/php-composer-reader)
[![Latest Stable Version](https://poser.pugx.org/nadar/php-composer-reader/v/stable)](https://packagist.org/packages/nadar/php-composer-reader)
[![Total Downloads](https://poser.pugx.org/nadar/php-composer-reader/downloads)](https://packagist.org/packages/nadar/php-composer-reader)
[![License](https://poser.pugx.org/nadar/php-composer-reader/license)](https://packagist.org/packages/nadar/php-composer-reader)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/test_coverage)](https://codeclimate.com/github/nadar/php-composer-reader/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/maintainability)](https://codeclimate.com/github/nadar/php-composer-reader/maintainability)

A PHP library to manipulate and read the **composer.json** file, free from any dependencies.

## Usage

Load the composer.json file into the ComposerReader:

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

Get an array of objects for each Package in the require section of the composer.json file:

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new RequireSection($reader);

foreach($section as $package) {
    echo $package->name . ' with ' . $package->constraint;
}
```

Get an array of objects for each PSR defintion in the autoload section of the composer.json file:

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new AutoloadSection($reader, AutoloadSection::TYPE_PSR4);

foreach ($section as $autoload) {
    echo $autoload->namespace . ' with ' . $autoload->source;
}
```

Add a new psr autoload definition into an existing composer.json file and save it:

```php
// create reader
$reader = new ComposerReader('path/to/composer.json');

// generate new autoload section object
$new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);

// store the new autoload object into the autoload section
$section = new AutoloadSection($reader);
$section->add($new)->save();
```
