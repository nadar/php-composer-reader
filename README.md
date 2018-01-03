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
    echo $package->name . ' with ' . $package->constraint;
}
```

Get autoloading section data.

```php
$reader = new ComposerReader('path/to/composer.json');
$section = new AutoloadSection($reader, AutoloadSection::TYPE_PSR4);

foreach ($section as $autoload) {
    echo $autoload->namespace . ' with ' . $autoload->source;
}
```

Add and save new autoload data

```php
$reader = new ComposerReader('path/to/composer.json');

$new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);

$section = new AutoloadSection($reader);
$section->add($new)->save();
```