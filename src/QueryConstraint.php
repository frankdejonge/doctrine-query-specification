<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\QueryBuilder;

/**
 * A query constraint influences building of a doctrine query object. You can
 * use the query constraint to compose WHERE conditions. The end result is applied
 * to a query using a `where` invocation on doctrine's query builder.
 */
interface QueryConstraint extends QuerySpecification
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return ?object
     */
    public function asQueryConstraint(QueryBuilder $queryBuilder, string $rootAlias): ?object;
}
