<?php

/**
 * League CSV Doctrine Collection Bridge (https://github.com/bakame-php/csv-doctrine-bridge).
 *
 * @author  Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license https://github.com/bakame-php/csv-doctrine-bridge/blob/master/LICENSE (MIT License)
 * @version 1.0.0
 * @link    https://github.com/bakame-php/csv-doctrine-bridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BakameTest\Csv\Doctrine\Bridge;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use League\Csv\Reader;
use League\Csv\ResultSet;
use PHPUnit\Framework\TestCase;
use function Bakame\Csv\Doctrine\Bridge\convert;

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

        $records = convert($criteria)->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
        self::assertTrue(count($records) <= 5);
    }

    public function testAdapterWithoutExpression()
    {
        $criteria = new Criteria(null, ['annee' => 'ASC'], 0, 5);
        $records = convert($criteria)->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
        self::assertCount(5, $records);
    }

    public function testAdapterWithoutOrdering()
    {
        $criteria = new Criteria(new Comparison('prenoms', '=', 'Adam'));
        $records = convert($criteria)->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
    }

    public function testAdapterWithoutInterval()
    {
        $expr = new Comparison('prenoms', '=', 'Adam');
        $criteria = new Criteria($expr, ['annee' => 'ASC']);

        $records = convert($criteria)->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
    }

    public function testAdapterWithEmptyCriteria()
    {
        $records = convert(new Criteria())->process($this->csv);
        self::assertInstanceOf(ResultSet::class, $records);
    }
}
