<?php

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
     * @var QuerySpecificationInterface[]
     */
    private $specifications;

    /**
     * @param QuerySpecificationInterface[] $specifications
     */
    public function __construct(array $specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * @inheritdoc
     */
    public function asQueryConstraint(QueryBuilder $queryBuilder, $rootAlias): ?object
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
    public function modifyQuery(Query $query, $rootAlias): void
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
    public function modifyQueryBuilder(QueryBuilder $queryBuilder, $rootAlias): void
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
    public static function all(array $specifications)
    {
        return new All($specifications);
    }

    /**
     * @param array $specifications
     *
     * @return Any
     */
    public static function any(array $specifications)
    {
        return new Any($specifications);
    }
}
