League CSV - Doctrine Collection Bridge
=======

This package contains classes to convert [League Csv](https://csv.thephpleague.com) objects into [Doctrine Collections](https://www.doctrine-project.org/projects/collections.html) objects.

```php
<?php

use Bakame\Csv\Doctrine\Bridge\Collection;
use Bakame\Csv\Doctrine\Bridge\CriteriaAdapter;
use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;

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

$adapter = new CriteriaAdapter($criteria);
$resultset = $adapter->process($csv);
$result = new Collection($resultset);

//or

$collection = new Collection($csv);
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

use Bakame\Csv\Doctrine\Bridge\Collection;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$collection = new Collection($csv);
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

$collection = new Collection($stmt->process($csv));
```

### Using Doctrine Criteria to filter a `League\Csv\Reader` object

```php
<?php

use Bakame\Csv\Doctrine\Bridge\CriteriaAdapter;
use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/my/file.csv');
$csv->setHeaderOffset(0);
$csv->setDelimiter(';');

$criteria = Criteria::create()
    ->andWhere(Criteria::expr()->eq('name', 'Adam'))
    ->orderBy(['years', 'ASC'])
    ->setFirstResult(0)
    ->setMaxResults(10)
;

$adapter = new CriteriaAdapter($criteria);
$resultset = $adapter->process($csv);
```

### CriteriaAdapter advanced usages

```php
<?php

use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;

public CriteriaAdapter::getStatement(): Statement
public CriteriaAdapter::process(Reader $reader, array $header = []): ResultSet
public static CriteriaAdapter::addWhere(Statement $stmt, Criteria $criteria): Statement
public static CriteriaAdapter::addOrderBy(Statement $stmt, Criteria $criteria): Statement
public static CriteriaAdapter::addInterval(Statement $stmt, Criteria $criteria): Statement
```

- `CriteriaAdapter::getStatement` converts the current `Criteria` object into a `Statement` object.
- `CriteriaAdapter::process` filters a `Reader` object using the current `Criteria` object and returns a `ResultSet`.
- `CriteriaAdapter::addWhere` adds the `Criteria::getWhereExpression` filters to the submitted `Statement` object.
- `CriteriaAdapter::addOrderBy` adds the `Criteria::getOrderings` filters to the submitted `Statement` object.
- `CriteriaAdapter::addInterval` adds the `Criteria::getFirstResult` and `Criteria::getMaxResults` filters to the submitted `Statement` object.

**WARNING: While the `Criteria` object is mutable the `Statement` object is immutable.**

- All returned `Statement` objects are new instances;
- Calling multiple times the `CriteriaAdapter::process` method while changing the `Criteria` state may result in different `ResultSet` with the same CSV document;

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