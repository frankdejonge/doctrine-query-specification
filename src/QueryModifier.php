<?php

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\Query;

interface QueryModifier extends QuerySpecification
{
    /**
     * Modify the Query object.
     *
     * @param Query  $query
     * @param string $rootAlias
     */
    public function modifyQuery(Query $query, $rootAlias);
}
