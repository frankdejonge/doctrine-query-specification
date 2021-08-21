<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class FieldEquals implements QueryConstraint
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function asQueryConstraint(QueryBuilder $queryBuilder, string $rootAlias): ?object
    {
        $expr = $queryBuilder->expr();
        $queryBuilder->setParameter($this->field, $this->value);

        return $expr->eq("{$rootAlias}.{$this->field}", ":{$this->field}");
    }
}
