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

use function array_reverse;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use League\Csv\Statement;

final class CriteriaConverter
{
    /**
     * Returns the Statement object created from the current Criteria object.
     *
     */
    public static function convert(Criteria $criteria, Statement $stmt = null): Statement
    {
        $stmt = self::addWhere($criteria, $stmt);
        $stmt = self::addOrderBy($criteria, $stmt);

        return self::addInterval($criteria, $stmt);
    }

    /**
     * Returns a Statement instance with the Criteria::getWhereExpression filter.
     *
     * This method MUST retain the state of the Statement instance, and return
     * an new Statement instance with the added Criteria::getWhereExpression filter.
     *
     */
    public static function addWhere(Criteria $criteria, Statement $stmt = null): Statement
    {
        $stmt = $stmt ?? new Statement();
        $expr = $criteria->getWhereExpression();
        if (null === $expr) {
            return $stmt;
        }

        return $stmt->where((new ClosureExpressionVisitor())->dispatch($expr));
    }

    /**
     * Returns a Statement instance with the Criteria::getOrderings filter.
     *
     * This method MUST retain the state of the Statement instance, and return
     * an new Statement instance with the added Criteria::getOrderings filter.
     *
     */
    public static function addOrderBy(Criteria $criteria, Statement $stmt = null): Statement
    {
        $next = null;
        foreach (array_reverse($criteria->getOrderings()) as $field => $ordering) {
            $next = ClosureExpressionVisitor::sortByField(
                $field,
                $ordering === Criteria::DESC ? -1 : 1,
                $next
            );
        }

        $stmt = $stmt ?? new Statement();
        if (null === $next) {
            return $stmt;
        }

        return $stmt->orderBy($next);
    }

    /**
     * Returns a Statement instance with the Criteria interval parameters.
     *
     * This method MUST retain the state of the Statement instance, and return
     * an new Statement instance with the added Criteria::getFirstResult
     * and Criteria::getMaxResults filters paramters.
     *
     */
    public static function addInterval(Criteria $criteria, Statement $stmt = null): Statement
    {
        $offset = $criteria->getFirstResult() ?? 0;
        $length = $criteria->getMaxResults() ?? -1;
        $stmt = $stmt ?? new Statement();

        return $stmt->offset($offset)->limit($length);
    }
}
