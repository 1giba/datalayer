# Datalayer

> A simplest abstraction layer for eloquent models

## Installation

```sh
$ composer require 1giba/datalayer
```

## PSR-2

```sh
$ ./vendor/bin/php-cs-fixer fix ./src
```

## PHPStan

```sh
$ ./vendor/bin/phpstan analyse -l 4 -c phpstan.neon src
```

## PHPMD

```sh
$ ./vendor/bin/phpmd ./src text cleancode,codesize,controversial,design,naming,unusedcode
```