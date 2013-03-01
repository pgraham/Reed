# Reed:

Reed is a collection of useful utility functionality.

Reed requires PHP &gt;= 5.3.0

## Install

 1. [Install composer](http://getcomposer.org/doc/00-intro.md#globally)
 2. Create composer.json or add to existing:
    {
        "require": {
            "zeptech\utility": "1.x"
        }
    }
 3. Run `$ composer install`
 4. Add `require_once 'vendor/autoload.php';` to your common script.

## Tests:

 1. [Install composer](http://getcomposer.org/doc/00-intro.md#globally)
 2. `$ composer install --dev`
 3. `$ vendor/bin/phpunit test/`

## Api:

 1. Install [PHP Doctor](http://peej.github.com/phpdoctor/)
 4. From root directory of Reed installation issue command:
    `$ phpdoc phpdoc.ini`

This will generate a set of API Pages in /reed/api
