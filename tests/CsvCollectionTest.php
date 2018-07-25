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

use ArrayAccess;
use Bakame\Csv\Doctrine\Bridge\CsvCollection;
use Countable;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use League\Csv\Reader;
use League\Csv\Statement;
use PHPUnit\Framework\TestCase;
use TypeError;

class CsvCollectionTest extends TestCase
{
    protected $csv;

    protected function setUp()
    {
        $this->csv = Reader::createFromPath(__DIR__.'/data/prenoms.csv');
        $this->csv->setDelimiter(';');
        $this->csv->setHeaderOffset(0);
    }

    public function testConstructorWithReader()
    {
        $collection = new CsvCollection($this->csv);
        self::assertInstanceOf(Collection::class, $collection);
        self::assertInstanceOf(IteratorAggregate::class, $collection);
        self::assertInstanceOf(Countable::class, $collection);
        self::assertInstanceOf(ArrayAccess::class, $collection);
    }

    public function testConstructorWithResultSet()
    {
        $stmt = (new Statement())
            ->offset(10)
            ->limit(15)
        ;
        $result = $stmt->process($this->csv);
        $collection = new CsvCollection($result);
        self::assertInstanceOf(Collection::class, $collection);
        self::assertInstanceOf(IteratorAggregate::class, $collection);
        self::assertInstanceOf(Countable::class, $collection);
        self::assertInstanceOf(ArrayAccess::class, $collection);
    }

    public function testConstructorThrowsTypeError()
    {
        self::expectException(TypeError::class);
        new CsvCollection([]);
    }

    public function testDoInitialize()
    {
        $stmt = (new Statement())
            ->offset(10)
            ->limit(15)
        ;
        $result = $stmt->process($this->csv);

        $collection = new CsvCollection($result);
        self::assertCount(15, $collection);
    }
}
