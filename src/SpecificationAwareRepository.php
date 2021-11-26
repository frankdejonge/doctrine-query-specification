<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\Query;

interface SpecificationAwareRepository
{
    public function findBySpecification(QuerySpecification $specification): iterable;

    public function findOneBySpecification(QuerySpecification $specification): ?object;

    public function createQuerySatisfiedBy(QuerySpecification $specification, string $rootAlias): Query;
}
