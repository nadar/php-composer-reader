# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## 2.0.0 (2. April 2023)

+ **Remove support for PHP 7.**
+ Added support for PHP 8.0 - 8.2.
+ Added phpstan, rector and php cs fixer as GitHub Action automation.
+ Remove Travis, replaced with GitHub Actions

## 1.3.0 (17. June 2019)

+ [#7](https://github.com/nadar/php-composer-reader/issues/7) Added remove() method for require(-dev) and autoload(-dev) sections.
+ [#6](https://github.com/nadar/php-composer-reader/issues/6) Added add() method for require and require-dev sections.
+ Moved interfaces into interface folder.
+ Added AutoloadDevSection
+ Fixed bug in composer reader when file is unable to write.
+ Remove unused Iterator object in section iterators.

## 1.2.0 (30. April 2019)

+ Added feature for whole section removal
+ Added tests for php 7.3

## 1.1.0 (21. December 2018)

+ Add version constraint compare function.
+ Add generic iterator class.
+ Added require-dev section reader

## 1.0.1 (24. September 2018)

+ Add continuous testing integration.
+ Added coverage integration
+ Added basic phpdocs.

## 1.0.0 (3. January 2018)

+ First stable API release.
