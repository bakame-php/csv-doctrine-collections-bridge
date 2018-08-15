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

declare(strict_types=1);

namespace Bakame\Csv\Doctrine\Collection\Bridge;

use Doctrine\Common\Collections\Criteria;
use League\Csv\Statement;

/**
 * Returns the Statement object created from the current Criteria object.
 *
 * @see Converter::convert
 *
 * @param null|Statement $stmt
 */
function convert(Criteria $criteria, Statement $stmt = null): Statement
{
    return Converter::convert($criteria, $stmt);
}
