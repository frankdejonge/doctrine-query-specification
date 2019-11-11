<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\EntityRepository;

class SpecificationAwareEntityRepository extends EntityRepository implements SpecificationAwareRepository
{
    use SpecificationAwareRepositoryTrait;
}
