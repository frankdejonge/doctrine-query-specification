<?php

namespace FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Composite;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

class Any extends SpecificationCollection
{
    /**
     * @inheritdoc
     */
    protected function createCompositeConstraint(Expr $expression, array $conditions): Composite
    {
        return $expression->orX(...$conditions);
    }
}
