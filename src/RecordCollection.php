<?php

/**
 * League CSV Doctrine Collection Bridge (https://github.com/bakame-php/csv-doctrine-bridge)
 *
 * @author  Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license https://github.com/bakame-php/csv-doctrine-bridge/blob/master/LICENSE (MIT License)
 * @version 1.0.0
 * @link    https://github.com/bakame-php/csv-doctrine-bridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bakame\Csv\Extension;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use League\Csv\TabularDataReader;

final class RecordCollection extends AbstractLazyCollection implements Selectable
{
    /**
     * @var TabularDataReader
     */
    private $tabularDataReader;

    public function __construct(TabularDataReader $tabularDataReader)
    {
        $this->tabularDataReader = $tabularDataReader;
    }

    /**
     * {@inheritDoc}
     */
    protected function doInitialize(): void
    {
        $this->collection = new ArrayCollection();
        foreach ($this->tabularDataReader as $offset => $record) {
            $this->collection[$offset] = $record;
        }
        unset($this->tabularDataReader);
    }

    /**
     * {@inheritDoc}
     */
    public function matching(Criteria $criteria): ArrayCollection
    {
        $this->initialize();

        /** @var ArrayCollection $collection */
        $collection = $this->collection;

        /** @var ArrayCollection $newCollection */
        $newCollection = $collection->matching($criteria);

        return $newCollection;
    }
}
