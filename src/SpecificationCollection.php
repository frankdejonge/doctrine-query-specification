<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection\All;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection\Any;

abstract class SpecificationCollection implements QueryConstraint, QueryModifier, QueryBuilderModifier
{
    /**
     * @var QuerySpecification[]
     */
    private array $specifications;

    public function __construct(QuerySpecification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function asQueryConstraint(QueryBuilder $queryBuilder, string $rootAlias): ?object
    {
        $constraintsFilter = function ($specification) {
            return $specification instanceof QueryConstraint;
        };

        $conditionMapper = function (QuerySpecification $specification) use ($queryBuilder, $rootAlias) {
            return $specification->asQueryConstraint($queryBuilder, $rootAlias);
        };

        $queryConstraints = array_filter($this->specifications, $constraintsFilter);
        $conditions = array_filter(array_map($conditionMapper, $queryConstraints));

        return count($conditions) === 0
            ? null
            : $this->createCompositeConstraint($queryBuilder->expr(), $conditions);
    }

    public function modifyQuery(Query $query, string $rootAlias): void
    {
        foreach ($this->specifications as $specification) {
            if ($specification instanceof QueryModifier) {
                $specification->modifyQuery($query, $rootAlias);
            }
        }
    }

    public function modifyQueryBuilder(QueryBuilder $queryBuilder, string $rootAlias): void
    {
        foreach ($this->specifications as $specification) {
            if ($specification instanceof QueryBuilderModifier) {
                $specification->modifyQueryBuilder($queryBuilder, $rootAlias);
            }
        }
    }

    abstract protected function createCompositeConstraint(Expr $expression, array $conditions): Composite;

    public static function all(QuerySpecification ...$specifications): All
    {
        return new All(...$specifications);
    }

    public static function any(QuerySpecification ...$specifications): Any
    {
        return new Any(...$specifications);
    }
}
