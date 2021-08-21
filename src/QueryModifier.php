<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\Query;

/**
 * After all the constraints have been applied a query is created. This
 * query can be manipulated. Cases for query modification are:
 *
 *      - Setting hydration mode
 *      - Limiting results
 *      - Ordering a result set
 */
interface QueryModifier extends QuerySpecification
{
    public function modifyQuery(Query $query, string $rootAlias): void;
}
