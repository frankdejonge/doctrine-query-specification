<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderModifier extends QuerySpecification
{
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, string $rootAlias): void;
}
