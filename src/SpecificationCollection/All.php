<?php

namespace FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

use Doctrine\ORM\Query\Expr;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

class All extends SpecificationCollection
{
    /**
     * @inheritdoc
     */
    protected function createCompositeConstraint(Expr $expression, array $conditions)
    {
        return $expression->andX(...$conditions);
    }
}