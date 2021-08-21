<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryBuilderModifier;

class QueryBuilderModifierSpy implements QueryBuilderModifier
{
    /**
     * @var bool
     */
    private $called = false;


    /**
     * Modify the query
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     */
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, string $rootAlias): void
    {
        $this->called = true;
    }

    /**
     * @return bool
     */
    public function wasCalled(): bool
    {
        return $this->called;
    }
}
