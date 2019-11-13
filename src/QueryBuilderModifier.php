<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderModifier extends QuerySpecification
{
    /**
     * Modify the query
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     */
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, string $rootAlias): void;
}
