<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

interface SpecificationAwareRepository
{
    /**
     * Find all entities that match a given specification.
     *
     * @param QuerySpecification $specification
     *
     * @return iterable
     */
    public function findBySpecification(QuerySpecification $specification): iterable;

    /**
     * Find one entity that matches a given specification.
     *
     * @param QuerySpecification $specification
     *
     * @return object|null
     */
    public function findOneBySpecification(QuerySpecification $specification): ?object;
}
