<?php

namespace FrankDeJonge\DoctrineQuerySpecification;

use Doctrine\ORM\EntityRepository;

class SpecificationAwareEntityRepository extends EntityRepository implements SpecificationAwareRepository
{
    use SpecificationAwareRepositoryTrait;
}