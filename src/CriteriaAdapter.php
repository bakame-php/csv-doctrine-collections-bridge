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

namespace Bakame\Csv\Adapter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;
use function array_reverse;

final class CriteriaAdapter
{
    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * New instance.
     *
     * @param null|Criteria $criteria
     */
    public function __construct(Criteria $criteria = null)
    {
        $this->criteria = $criteria ?? new Criteria();
    }

    /**
     * Process a Reader object using the current criteria object.
     */
    public function process(Reader $reader): ResultSet
    {
        return $this->getStatement()->process($reader);
    }

    /**
     * Returns the Statement object created from the current Criteria object.
     */
    public function getStatement(): Statement
    {
        $stmt = self::addWhere(new Statement(), $this->criteria);
        $stmt = self::addOrderBy($stmt, $this->criteria);
        $stmt = self::addInterval($stmt, $this->criteria);

        return $stmt;
    }

    /**
     * Returns a Statement instance with the Criteria::getWhereExpression filter.
     *
     * This method MUST retain the state of the Statement instance, and return
     * an new Statement instance with the added Criteria::getWhereExpression filter.
     */
    public static function addWhere(Statement $stmt, Criteria $criteria): Statement
    {
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
     */
    public static function addOrderBy(Statement $stmt, Criteria $criteria): Statement
    {
        $orderings = $criteria->getOrderings();
        $next = null;
        foreach (array_reverse($orderings) as $field => $ordering) {
            $next = ClosureExpressionVisitor::sortByField($field, $ordering === Criteria::DESC ? -1 : 1, $next);
        }

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
     */
    public static function addInterval(Statement $stmt, Criteria $criteria): Statement
    {
        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();
        if (null === $offset) {
            $offset = 0;
        }

        if (null === $length) {
            $length = -1;
        }

        return $stmt->offset($offset)->limit($length);
    }
}
