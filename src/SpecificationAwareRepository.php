<?php

namespace FrankDeJonge\DoctrineQuerySpecification;

interface SpecificationAwareRepository
{
    /**
     * Find all entities that match a given specification.
     *
     * @param QuerySpecification $specification
     *
     * @return array
     */
    public function findBySpecification(QuerySpecification $specification);

    /**
     * Find one entity that matches a given specification.
     *
     * @param QuerySpecification $specification
     *
     * @return object|null
     */
    public function findOneBySpecification(QuerySpecification $specification);
}