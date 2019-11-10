<?php

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\Query;
use FrankDeJonge\DoctrineQuerySpecification\QueryModifier;

class DummyQueryModifier implements QueryModifier
{
    private $called = false;

    /**
     * Modify the Query object.
     *
     * @param Query  $query
     * @param string $rootAlias
     */
    public function modifyQuery(Query $query, $rootAlias): void
    {
        $this->called = true;
    }

    /**
     * @return boolean
     */
    public function wasCalled()
    {
        return $this->called;
    }
}
