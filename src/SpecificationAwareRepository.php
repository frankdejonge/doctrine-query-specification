<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

interface SpecificationAwareRepository
{
    public function findBySpecification(QuerySpecification $specification): iterable;

    public function findOneBySpecification(QuerySpecification $specification): ?object;
}
