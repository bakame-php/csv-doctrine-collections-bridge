League CSV - Doctrine Criteria Adapter
=======

This package contains an `Doctrine\Common\Collections\Criteria` adapter for `League\Csv` version 9+. Using the adapter you can use Doctrine semantic to filter CSV records.

```php
<?php

use Bakame\Csv\Adapter\CriteriaAdapter;
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

$adapter = new CriteriaAdapter($criteria);
$result = $adapter->process($csv);
```

System Requirements
-------

You need:

- **PHP >= 7.1** but the latest stable version of PHP is recommended.

Installation
--------

```bash
$ composer require bakame/league-csv-criteria-adapter
```

FAQ
---------

Why create a standalone package for a single class ?

- Because we don't want `League\Csv` to depend on another PHP package.

Why not make `League\Csv` returns a `Doctrine` collections then ?

- Same answer. And also Doctrine collections out of the box works mainly with `array` while `League\Csv` heavily uses `Iterator` objects.

Documentation
--------

```php
<?php

namespace Bakame\Csv\Adapter;

use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;

final class CriteriaAdapter
{
    public function __construct(Criteria $criteria = null);
    public function getStatement(): Statement
    public function process(Reader $reader): ResultSet
    public static function addWhere(Statement $stmt, Criteria $criteria): Statement
    public static function addOrderBy(Statement $stmt, Criteria $criteria): Statement
    public static function addInterval(Statement $stmt, Criteria $criteria): Statement
}
```

- `CriteriaAdapter::getStatement` converts the current `Criteria` object into a `League\Csv\Statement` object.
- `CriteriaAdapter::addWhere` returns a new instance of the submitted `League\Csv\Statement` with the  `Criteria::getWhereExpression` filters attached to it.
- `CriteriaAdapter::addOrderBy` returns a new instance of the submitted `League\Csv\Statement` with the  `Criteria::getOrderings` filters attached to it.
- `CriteriaAdapter::addInterval` returns a new instance of the submitted `League\Csv\Statement` with `Criteria::getFirstResult` and `Criteria::getMaxResults` attached to it.

**WARNING: While the `Doctrine\Common\Collections\Criteria` object is mutable the `League\Csv\Statement` object is immutable. So calling multiple time the `CriteriaAdapter::process` method while changing the `Criteria` state may result in different `ResultSet` with the same CSV document.**

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