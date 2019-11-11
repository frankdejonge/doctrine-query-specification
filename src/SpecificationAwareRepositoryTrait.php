<?php

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

trait SpecificationAwareRepositoryTrait
{
    /**
     * Find all entities that match a given specification.
     *
     * @param QuerySpecification $specification
     * @param string             $rootAlias
     *
     * @return iterable
     */
    public function findBySpecification(QuerySpecification $specification, string $rootAlias = 'root'): iterable
    {
        return $this->createQuerySatisfiedBy($specification, $rootAlias)->getResult();
    }

    /**
     * Find an entity that matches a given specification.
     *
     * @param QuerySpecification $specification
     * @param string             $rootAlias
     *
     * @return null|object
     * @throws NonUniqueResultException
     */
    public function findOneBySpecification(QuerySpecification $specification, string $rootAlias = 'root'): ?object
    {
        return $this->createQuerySatisfiedBy($specification, $rootAlias)->getOneOrNullResult();
    }

    /**
     * @param QuerySpecification          $specification
     * @param                             $rootAlias
     *
     * @return Query
     */
    protected function createQuerySatisfiedBy(QuerySpecification $specification, string $rootAlias): Query
    {
        $queryBuilder = $this->createQueryBuilder($rootAlias);
        $this->applyQueryConstraint($specification, $rootAlias, $queryBuilder);

        if ($specification instanceof QueryBuilderModifier) {
            $specification->modifyQueryBuilder($queryBuilder, $rootAlias);
        }

        $query = $queryBuilder->getQuery();

        if ($specification instanceof QueryModifier) {
            $specification->modifyQuery($query, $rootAlias);
        }

        return $query;
    }


    /**
     * @param QuerySpecification $specification
     * @param string          $rootAlias
     * @param QueryBuilder    $queryBuilder
     */
    private function applyQueryConstraint(QuerySpecification $specification, string $rootAlias, QueryBuilder $queryBuilder): void
    {
        if ( ! $specification instanceof QueryConstraint) {
            return;
        }

        $constraints = $specification->asQueryConstraint($queryBuilder, $rootAlias);

        if ($constraints) {
            $queryBuilder->where($constraints);
        }
    }

    /**
     * Create a new QueryBuilder instance that is pre-populated for this entity name.
     *
     * @param string $alias
     * @param null   $indexBy
     *
     * @return QueryBuilder
     */
    abstract public function createQueryBuilder($alias, $indexBy = null);
}
