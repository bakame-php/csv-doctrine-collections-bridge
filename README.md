League CSV - Doctrine Collection Bridge
=======

[![Latest Version](https://img.shields.io/github/release/bakame-php/csv-doctrine-collections-bridge.svg?style=flat-square)](https://github.com/bakame-php/csv-doctrine-collections-bridge/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/bakame-php/csv-doctrine-bridge.svg?branch=master)](https://travis-ci.org/bakame-php/csv-doctrine-bridge)

This package contains:

- a class to convert [League Csv](https://csv.thephpleague.com) objects into [Doctrine Collections](https://www.doctrine-project.org/projects/collections.html) objects.
- a class to enable using [Doctrine Collections powerful Expression API](https://www.doctrine-project.org/projects/doctrine-collections/en/latest/expressions.html) on League Csv objects.

```php
<?php

use Bakame\Csv\Extension\RecordCollection;
use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use function Bakame\Csv\Extension\criteria_convert;

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

$resultset = criteria_convert($criteria)->process($csv);
$result = new RecordCollection($resultset);

//or

$collection = new RecordCollection($csv);
$result = $collection->matching($criteria);
```

System Requirements
-------

You need:

- At least **League/Csv 9.6 and PHP7.2** but the latest stable version of each dependency is recommended.

Installation
--------

```bash
$ composer require bakame/csv-doctrine-collection-bridge
```

Usage
--------

### Converting a `League\Csv\Reader` into a Doctrine Collection object.

```php
<?php

use Bakame\Csv\Extension\RecordCollection;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$collection = new RecordCollection($csv);
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

$collection = new RecordCollection($stmt->process($csv));
```

### Using Doctrine Criteria to filter a `League\Csv\Reader` object

You can simply use the provided `Bakame\Csv\Extension\criteria_convert` function to convert a `Doctrine\Common\Collections\Criteria` object into a `League\Csv\Statement` one.

```php
<?php

use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use function Bakame\Csv\Extension\criteria_convert;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$criteria = Criteria::create()
    ->andWhere(Criteria::expr()->eq('name', 'Adam'))
    ->orderBy(['years', 'ASC'])
    ->setFirstResult(0)
    ->setMaxResults(10)
;

$stmt = criteria_convert($criteria);
$resultset = $stmt->process($csv);
```

### CriteriaConverter advanced usages

The `Bakame\Csv\Extension\criteria_convert` function is an alias of the `CriteriaConverter::convert` method.

```php
<?php

use Doctrine\Common\Collections\Criteria;
use League\Csv\Statement;

public static CriteriaConverter::convert(Criteria $criteria, Statement $stmt = null): Statement
public static CriteriaConverter::addWhere(Criteria $criteria, Statement $stmt = null): Statement
public static CriteriaConverter::addOrderBy(Criteria $criteria, Statement $stmt = null): Statement
public static CriteriaConverter::addInterval(Criteria $criteria, Statement $stmt = null): Statement
```

- `CriteriaConverter::convert` converts the `Criteria` object into a `Statement` object.
- `CriteriaConverter::addWhere` adds the `Criteria::getWhereExpression` filters to the submitted `Statement` object.
- `CriteriaConverter::addOrderBy` adds the `Criteria::getOrderings` filters to the submitted `Statement` object.
- `CriteriaConverter::addInterval` adds the `Criteria::getFirstResult` and `Criteria::getMaxResults` filters to the submitted `Statement` object.

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
