Reed:
-----

Reed is a collection of useful utility functionality.

Reed requires PHP >= 5.3.0

Tests:
------

Tests are written against phpunit-3.5.7

To run change into the test directory and issue command:
  phpunit AllTests

Api:
----

To generate API:
  1. Clone git://github.com/peej/phpdoctor.git
  2. Make phpdoc.php executable
  3. Put phpdoc.php in your environment path variable
  4. From root directory of Reed installation issue command:
    phpdoc phpdoc.ini

This will generate a set of API Pages in /reed/api
