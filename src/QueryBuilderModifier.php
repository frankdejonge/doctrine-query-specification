<?php

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
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, $rootAlias);
}