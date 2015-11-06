<?php

namespace FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

use Doctrine\ORM\Query\Expr;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

class Any extends SpecificationCollection
{
    /**
     * @inheritdoc
     */
    protected function createCompositeConstraint(Expr $expression, array $conditions)
    {
        return $expression->orX(...$conditions);
    }
}