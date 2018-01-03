# Composer read and manipulate library for PHP

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
$reader = new ComposerReader('path/to/composer.json');

$new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);

$section = new AutoloadSection($reader);
$section->add($new)->save();
```
