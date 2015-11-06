<?php

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

class QuerySpecificationTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([new DummyFixtures()]);
    }

    /**
     * @return DummyRepository
     */
    private function getDummyRepository()
    {
        return $this->getRepository(DummyEntity::class);
    }


    /**
     * @test
     */
    public function it_should_apply_constraints()
    {
        $repository = $this->getDummyRepository();
        /** @var DummyEntity $result */
        $result = $repository->findOneBySpecification(new DummyConstraint('id', 1, 'id'));
        $this->assertEquals(1, $result->getId());
        $collection = $repository->findBySpecification(new DummyConstraint('id', 2));
        $this->assertCount(1, $collection);
        $this->assertEquals(2, $collection[0]->getId());
    }

    /**
     * @test
     */
    public function it_should_invoke_query_modifiers()
    {
        $repository = $this->getDummyRepository();
        $spy = new DummyQueryModifier();
        $this->assertFalse($spy->wasCalled());
        $repository->findBySpecification($spy);
        $this->assertTrue($spy->wasCalled());
    }

    /**
     * @test
     */
    public function it_should_invoke_query_builder_modifiers()
    {
        $repository = $this->getDummyRepository();
        $spy = new DummyQueryBuilderModifier();
        $this->assertFalse($spy->wasCalled());
        $repository->findBySpecification($spy);
        $this->assertTrue($spy->wasCalled());
    }

    /**
     * @test
     */
    public function it_should_find_by_an_any_specification()
    {
        $any = SpecificationCollection::any([
            new DummyConstraint('id', 1),
            new DummyConstraint('value', 'second'),
        ]);

        $repository = $this->getDummyRepository();
        $result = $repository->findBySpecification($any);
        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function it_should_find_by_an_all_specification()
    {
        $modifierSpy = new DummyQueryModifier();
        $builderSpy = new DummyQueryBuilderModifier();
        $any = SpecificationCollection::all([
            new DummyConstraint('id', 1),
            $modifierSpy,
            $builderSpy,
        ]);

        $repository = $this->getDummyRepository();
        /** @var DummyEntity $result */
        $result = $repository->findOneBySpecification($any);
        $this->assertInstanceOf(DummyEntity::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('first', $result->getValue());
        $this->assertTrue($modifierSpy->wasCalled());
        $this->assertTrue($builderSpy->wasCalled());
    }

    /**
     * @test
     */
    public function it_should_not_require_constraints_in_collections()
    {
        $any = SpecificationCollection::all([
            new DummyQueryModifier(),
        ]);

        $result = $this->getDummyRepository()->findBySpecification($any);
        $this->assertCount(3, $result);
    }
}
