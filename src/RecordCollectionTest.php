<?php

/**
 * League CSV Doctrine Collection Bridge (https://github.com/bakame-php/csv-doctrine-bridge)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bakame\Csv\Extension;

use Doctrine\Common\Collections\Criteria;
use League\Csv\Reader;
use League\Csv\Statement;
use PHPUnit\Framework\TestCase;

final class RecordCollectionTest extends TestCase
{
    /**
     * @var Reader
     */
    protected $csv;

    protected function setUp(): void
    {
        $this->csv = Reader::createFromPath(__DIR__.'/data/prenoms.csv');
        $this->csv->setDelimiter(';');
        $this->csv->setHeaderOffset(0);
    }

    public function testConstructorWithReader(): void
    {
        $collection = new RecordCollection($this->csv);
        self::assertCount(10121, $collection);
    }

    public function testDoInitialize(): void
    {
        $stmt = (new Statement())
            ->offset(10)
            ->limit(15)
        ;
        $result = $stmt->process($this->csv);

        $collection = new RecordCollection($result);
        self::assertCount(15, $collection);
    }

    public function testMatching(): void
    {
        /** @var resource $fp */
        $fp = tmpfile();
        fputcsv($fp, ['foo', 'bar', 'baz']);
        fputcsv($fp, ['foofoo', 'barbar', 'bazbaz']);
        $csv = Reader::createFromStream($fp);
        $collection = new RecordCollection($csv);

        self::assertSame([
            ['foo', 'bar', 'baz'],
            ['foofoo', 'barbar', 'bazbaz'],
        ], $collection->matching(new Criteria(null, [0 => Criteria::ASC]))->toArray());

        $csv = null;
        fclose($fp);
    }
}
