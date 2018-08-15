League CSV - Doctrine Collection Bridge
=======

[![Latest Version](https://img.shields.io/github/release/bakame-php/csv-doctrine-collections-bridge.svg?style=flat-square)](https://github.com/bakame-php/csv-doctrine-collections-bridge/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/bakame-php/csv-doctrine-bridge.svg?branch=master)](https://travis-ci.org/bakame-php/csv-doctrine-bridge)

This package contains classes to convert [League Csv](https://csv.thephpleague.com) objects into [Doctrine Collections](https://www.doctrine-project.org/projects/collections.html) objects.

```php
<?php

use Bakame\Csv\Doctrine\Collection\Bridge\Records;
use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use function Bakame\Csv\Doctrine\Collection\Bridge\convert;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$criteria = Criteria::create()
    ->andWhere(Criteria::expr()->eq('prenom', 'Adam'))
    ->orderBy(['annee', 'ASC'])
    ->setFirstResult(0)
    ->setMaxResults(10)
;

//you can do

$resultset = convert($criteria)->process($csv);
$result = new Records($resultset);

//or

$collection = new Records($csv);
$result = $collection->matching($criteria);
```

System Requirements
-------

You need:

- **PHP >= 7.1** but the latest stable version of PHP is recommended.

Installation
--------

```bash
$ composer require bakame/league-csv-doctrine-bridge
```

Usage
--------

### Converting a `League\Csv\Reader` into a Doctrine Collection object.

```php
<?php

use Bakame\Csv\Doctrine\Collection\Bridge\Records;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$collection = new Records($csv);
```

### Converting a `League\Csv\ResultSet` into a Doctrine Collection object.

```php
<?php

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$stmt = (new Statement())
    ->where(function (array $row) {
        return isset($row['email'])
            && false !== strpos($row['email'], '@github.com');
    });

$collection = new Records($stmt->process($csv));
```

### Using Doctrine Criteria to filter a `League\Csv\Reader` object

You can simply use the provided `Bakame\Csv\Doctrine\Collection\Bridge\convert` function to convert a `Doctrine\Common\Collections\Criteria` object into a `League\Csv\Statement` one.

```php
<?php

use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use function Bakame\Csv\Doctrine\Collection\Bridge\convert;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$criteria = Criteria::create()
    ->andWhere(Criteria::expr()->eq('name', 'Adam'))
    ->orderBy(['years', 'ASC'])
    ->setFirstResult(0)
    ->setMaxResults(10)
;

$stmt = convert($criteria);
$resultset = $stmt->process($csv);
```

### Converter advanced usages

The `Bakame\Csv\Doctrine\Collection\Bridge\convert` function is an alias of the `Converter::convert` method.

```php
<?php

use Doctrine\Common\Collections\Criteria;
use League\Csv\Statement;

public static Converter::convert(Criteria $criteria, Statement $stmt = null): Statement
public static Converter::addWhere(Criteria $criteria, Statement $stmt = null): Statement
public static Converter::addOrderBy(Criteria $criteria, Statement $stmt = null): Statement
public static Converter::addInterval(Criteria $criteria, Statement $stmt = null): Statement
```

- `Converter::convert` converts the `Criteria` object into a `Statement` object.
- `Converter::addWhere` adds the `Criteria::getWhereExpression` filters to the submitted `Statement` object.
- `Converter::addOrderBy` adds the `Criteria::getOrderings` filters to the submitted `Statement` object.
- `Converter::addInterval` adds the `Criteria::getFirstResult` and `Criteria::getMaxResults` filters to the submitted `Statement` object.

**WARNING: While the `Criteria` object is mutable the `Statement` object is immutable. All returned `Statement` objects are new instances**

Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

Testing
-------

The library has a :

- a [PHPUnit](https://phpunit.de) test suite
- a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/).
- a code analysis compliance test suite using [PHPStan](https://github.com/phpstan/phpstan).

To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

Security
-------

If you discover any security related issues, please email nyamsprod@gmail.com instead of using the issue tracker.

Credits
-------

- [ignace nyamagana butera](https://github.com/nyamsprod)
- [All Contributors](https://github.com/bakame-php/league-csv-criteria-adapter/contributors)

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.