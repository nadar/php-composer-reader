# Composer JSON Reader and Manipulator

![Tests](https://github.com/nadar/php-composer-reader/workflows/Tests/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/nadar/php-composer-reader/v/stable)](https://packagist.org/packages/nadar/php-composer-reader)
[![Total Downloads](https://poser.pugx.org/nadar/php-composer-reader/downloads)](https://packagist.org/packages/nadar/php-composer-reader)
[![License](https://poser.pugx.org/nadar/php-composer-reader/license)](https://packagist.org/packages/nadar/php-composer-reader)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/test_coverage)](https://codeclimate.com/github/nadar/php-composer-reader/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/3d695b2ba5d4298e28fe/maintainability)](https://codeclimate.com/github/nadar/php-composer-reader/maintainability)

A small PHP library for manipulating and reading the **composer.json** file. It allows you to add new sections, check if it's writable/readable, or retrieve information from the composer schema such as description, title, and more.

## Installation

Install via Composer:

```sh
composer require nadar/php-composer-reader
```

## Usage

To load the composer.json file into ComposerReader:

```php
require 'vendor/autoload.php';

$reader = new ComposerReader('path/to/composer.json');

if (!$reader->canRead()) {
   throw new Exception("Unable to read the JSON file.");
}

if (!$reader->canWrite()) {
   throw new Exception("Unable to write to the JSON file.");
}

// Dump the full content
var_dump($reader->getContent());
```

### Reading Section Data

Retrieve an array of objects for each package in the `require` section of the composer.json file:

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new RequireSection($reader);

foreach ($section as $package) {
    echo $package->name . ' with ' . $package->constraint;

    // Check if the package version is greater than a given version constraint.
    if ($package->greaterThan('^6.5')) {
        echo "Numerous releases available!";
    }
}
```

Retrieve an array of objects for each PSR definition in the `autoload` section of the composer.json file:

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new AutoloadSection($reader, AutoloadSection::TYPE_PSR4);

foreach ($section as $autoload) {
    echo $autoload->namespace . ' with ' . $autoload->source;
}
```

The following section readers are available for the composer schema ([Composer Schema Documentation](https://getcomposer.org/doc/04-schema.md)):

| Section        | Class              |
|----------------|--------------------|
| `require`      | RequireSection     |
| `require-dev`  | RequireDevSection  |
| `autoload`     | AutoloadSection    |
| `autoload-dev` | AutoloadDevSection |

Additional schema information can be retrieved from the ComposerReader object with: `$reader->contentSection('extra', null);`

### Changing Section Data

Add a new PSR autoload definition to an existing composer.json file and save it:

```php
$reader = new ComposerReader('path/to/composer.json');

// Generate a new autoload section object
$new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);

// Store the new autoload object in the autoload section and save
$section = new AutoloadSection($reader);
$section->add($new)->save();
```

## Running Commands

To perform composer operations, use the `runCommand()` method:

```php
$reader = new ComposerReader('path/to/composer.json');
$reader->runCommand('dump-autoload'); // This is equivalent to running `composer dump-autoload`
```

This attempts to execute the dump-autoload command for the specified composer.json file. **This requires a globally installed Composer command** on your system ([Install Composer globally](https://getcomposer.org/doc/00-intro.md#globally)).

---
