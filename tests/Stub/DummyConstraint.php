<?php

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class DummyConstraint implements QueryConstraint
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    /**
     * DummyConstraint constructor.
     *
     * @param string $field
     * @param mixed  $value
     */
    public function __construct($field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function asQueryConstraint(QueryBuilder $queryBuilder, $rootAlias): ?object
    {
        $expr = $queryBuilder->expr();
        $queryBuilder->setParameter($this->field, $this->value);

        return $expr->eq("{$rootAlias}.{$this->field}", ":{$this->field}");
    }
}
