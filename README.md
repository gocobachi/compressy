# Compressy

[![License](https://img.shields.io/packagist/l/alchemy/Compressy.svg?style=flat-square)](https://github.com/gocobachi/compressy/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/alchemy/Compressy.svg?style=flat-square)](https://packagist.org/packages/gocobachi/compressy)
[![Travis](https://img.shields.io/travis/alchemy-fr/Compressy.svg?style=flat-square)](https://travis-ci.org/gocobachi/compressy)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/alchemy-fr/Compressy.svg?style=flat-square)](https://scrutinizer-ci.com/g/gocobachi/compressy/)
[![Packagist](https://img.shields.io/packagist/dt/alchemy/Compressy.svg?style=flat-square)](https://packagist.org/packages/gocobachi/compressy/stats)

A PHP library to read, create, and extract archives in various formats via command line utilities or PHP extensions

## Installation

The only supported installation method is via [Composer](https://getcomposer.org). Run the following command to require Compressy in your project:

```
composer require alchemy/Compressy
```

## Adapters

Compressy currently supports the following drivers and file formats:

- zip
  - .zip
- PHP zip extension
  - .zip
- GNU tar
  - .tar
  - .tar.gz
  - .tar.bz2
- BSD tar
  - .tar
  - .tar.gz
  - .tar.bz2

## Getting started

All the following code samples assume that Compressy is loaded and available as `$compressy`. You need the following code (or variation of) to load Compressy:

```
<?php

use Alchemy\Compressy\Compressy;

// Require Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Load Compressy
$compressy = Compressy::load();
```

### List an archive's contents:

```php
// Open an archive
$archive = $compressy->open('build.tar');

// Iterate through members
foreach ($archive as $member) {
    echo "Archive contains $member" . PHP_EOL;
}
```

### Extract an archive to a specific directory:

```php
// Open an archive
$archive = $compressy->open('build.tar');

// Extract archive contents to `/tmp`
$archive->extract('/tmp');
```

### Create a new archive

```php
// Creates an archive.zip that contains a directory "folder" that contains
// files contained in "/path/to/directory" recursively
$archive = $compressy->create('archive.zip', [
    'folder' => '/path/to/directory'
], true);
```

### Customize file and directory names inside archive

```php
$archive = $compressy->create('archive.zip', [
    'folder' => '/path/to/directory',            // will create a folder at root
    'http://www.google.com/logo.jpg',            // will create a logo.jpg file at root
    fopen('https://www.facebook.com/index.php'), // will create an index.php at root
    'directory/image.jpg' => 'image.jpg',        // will create a image.jpg in 'directory' folder
]);
```

## Documentation

Documentation hosted at [read the docs](https://compressy.dev/)!

## License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Extras

This project is a continuation of the Alchemy/Zippy package.
