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
use Bakame\Csv\Doctrine\Bridge\Collection;
use Countable;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use IteratorAggregate;
use League\Csv\Reader;
use League\Csv\Statement;
use PHPUnit\Framework\TestCase;
use TypeError;

class CollectionTest extends TestCase
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
        $collection = new Collection($this->csv);
        self::assertInstanceOf(DoctrineCollection::class, $collection);
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
        $collection = new Collection($result);
        self::assertInstanceOf(DoctrineCollection::class, $collection);
        self::assertInstanceOf(IteratorAggregate::class, $collection);
        self::assertInstanceOf(Countable::class, $collection);
        self::assertInstanceOf(ArrayAccess::class, $collection);
    }

    public function testConstructorThrowsTypeError()
    {
        self::expectException(TypeError::class);
        new Collection([]);
    }

    public function testDoInitialize()
    {
        $stmt = (new Statement())
            ->offset(10)
            ->limit(15)
        ;
        $result = $stmt->process($this->csv);

        $collection = new Collection($result);
        self::assertCount(15, $collection);
    }
}
