<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\QueryBuilder;

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
