<?php

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class DummyFieldIsNotNull implements QueryConstraint
{
    /**
     * @var string
     */
    private $field;

    /**
     * DummyFieldIsNotNull constructor.
     *
     * @param string $field
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    public function asQueryConstraint(QueryBuilder $queryBuilder, string $rootAlias): ?object
    {
        $expr = $queryBuilder->expr();

        return $expr->andX($expr->isNotNull("{$rootAlias}.{$this->field}"));
    }
}
