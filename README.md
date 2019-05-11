# Read and manipulate the composer.json file

[![Build Status](https://travis-ci.org/nadar/php-composer-reader.svg?branch=master)](https://travis-ci.org/nadar/php-composer-reader)
[![Latest Stable Version](https://poser.pugx.org/nadar/php-composer-reader/v/stable)](https://packagist.org/packages/nadar/php-composer-reader)
[![Total Downloads](https://poser.pugx.org/nadar/php-composer-reader/downloads)](https://packagist.org/packages/nadar/php-composer-reader)
[![License](https://poser.pugx.org/nadar/php-composer-reader/license)](https://packagist.org/packages/nadar/php-composer-reader)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/test_coverage)](https://codeclimate.com/github/nadar/php-composer-reader/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/maintainability)](https://codeclimate.com/github/nadar/php-composer-reader/maintainability)

A PHP library to manipulate and read the **composer.json** files.

## Install

Install via Composer

```sh
composer require nadar/php-composer-reader
```

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

### Read section data

Get an array of objects for each Package in the require section of the composer.json file:

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new RequireSection($reader);

foreach($section as $package) {
    echo $package->name . ' with ' . $package->constraint;

    // check if package version gerate then a version constraint.
    if ($package->greaterThan('^6.5')) {
        echo "A lot of releases already!";
    }
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

There are a few built in commands to read certain sections from the [composer schema](https://getcomposer.org/doc/04-schema.md):

|Section|Class
|-------|-----
|`require`|RequireSection
|`require-dev`|RequireDevSection
|`autoload`|AutoloadSection
|`autoload-dev`|AutoloadDevSection

All the other schema can be retrieved from the ComposerReader object trough: `$reader->contentSection('extra', null);`

### Change section data

Add a new psr autoload definition into an existing composer.json file and save it:

```php
$reader = new ComposerReader('path/to/composer.json');

// generate new autoload section object
$new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);

// store the new autoload object into the autoload section
$section = new AutoloadSection($reader);
$section->add($new)->save();
```

## Run commands

In order to perform composer operations you can use the `runCommand()` method:

```php
$reader = new ComposerReader('path/to/composer.json');
$reader->runCommand('dump-autoload'); // equals to `composer dump-autoload`
```

This will try to run the dump-autoload command on the specific composer.json file, of course this requires the the composer global binary
is installed on the current system (install composer globally: https://getcomposer.org/doc/00-intro.md#globally)
