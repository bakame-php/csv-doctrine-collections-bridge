<?php

/**
 * League CSV Doctrine Criteria Adapter (https://github.com/bakame-php/league-csv-criteria-adapter).
 *
 * @author  Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license https://github.com/bakame-php/league-csv-criteria-adapter/blob/master/LICENSE (MIT License)
 * @version 1.0.0
 * @link    https://github.com/bakame-php/league-csv-criteria-adapter
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BakameTest\Csv\Adapter;

use Bakame\Csv\Adapter\CriteriaAdapter;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;
use PHPUnit\Framework\TestCase;

class CriteriaAdapterTest extends TestCase
{
    protected $csv;

    protected function setUp()
    {
        $this->csv = Reader::createFromPath(__DIR__.'/data/prenoms.csv');
        $this->csv->setDelimiter(';');
        $this->csv->setHeaderOffset(0);
    }

    public function testAdapter()
    {
        $expr = new Comparison('prenoms', '=', 'Adam');
        $criteria = new Criteria($expr, ['annee' => 'ASC'], 0, 5);

        $adapter = new CriteriaAdapter($criteria);
        $records = $adapter->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
        self::assertTrue(count($records) <= 5);
    }

    public function testAdapterWithoutExpression()
    {
        $criteria = new Criteria(null, ['annee' => 'ASC'], 0, 5);
        $adapter = new CriteriaAdapter($criteria);
        $records = $adapter->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
        self::assertCount(5, $records);
    }

    public function testAdapterWithoutOrdering()
    {
        $criteria = new Criteria(new Comparison('prenoms', '=', 'Adam'));
        $adapter = new CriteriaAdapter($criteria);
        $records = $adapter->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
    }

    public function testAdapterWithoutInterval()
    {
        $expr = new Comparison('prenoms', '=', 'Adam');
        $criteria = new Criteria($expr, ['annee' => 'ASC']);

        $adapter = new CriteriaAdapter($criteria);
        $stmt = $adapter->getStatement();
        $records = $stmt->process($this->csv);
        self::assertInstanceOf(Statement::class, $stmt);
        self::assertInstanceOf(ResultSet::class, $records);
    }

    public function testAdapterWithEmptyCriteria()
    {
        $adapter = new CriteriaAdapter();
        $stmt = $adapter->getStatement();
        $records = $stmt->process($this->csv);
        self::assertInstanceOf(Statement::class, $stmt);
        self::assertInstanceOf(ResultSet::class, $records);
    }
}
