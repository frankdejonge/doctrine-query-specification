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
    private $specifications;

    /**
     * @param QuerySpecification[] $specifications
     */
    public function __construct(array $specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * @inheritdoc
     */
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

        return empty($conditions)
            ? null
            : $this->createCompositeConstraint($queryBuilder->expr(), $conditions);
    }

    /**
     * @inheritdoc
     */
    public function modifyQuery(Query $query, string $rootAlias): void
    {
        foreach ($this->specifications as $specification) {
            if ($specification instanceof QueryModifier) {
                $specification->modifyQuery($query, $rootAlias);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, string $rootAlias): void
    {
        foreach ($this->specifications as $specification) {
            if ($specification instanceof QueryBuilderModifier) {
                $specification->modifyQueryBuilder($queryBuilder, $rootAlias);
            }
        }
    }

    /**
     * @param Expr  $expression
     * @param array $conditions
     *
     * @return Composite
     */
    abstract protected function createCompositeConstraint(Expr $expression, array $conditions): Composite;

    /**
     * @param array $specifications
     *
     * @return All
     */
    public static function all(array $specifications): All
    {
        return new All($specifications);
    }

    /**
     * @param array $specifications
     *
     * @return Any
     */
    public static function any(array $specifications): Any
    {
        return new Any($specifications);
    }
}
