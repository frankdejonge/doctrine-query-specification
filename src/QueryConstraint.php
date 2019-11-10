<?php

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\QueryBuilder;

interface QueryConstraint extends QuerySpecification
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return mixed
     */
    public function asQueryConstraint(QueryBuilder $queryBuilder, $rootAlias): ?object;
}
