<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection;

class QuerySpecificationTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([new DummyFixtures()]);
    }

    /**
     * @return DummyRepository
     */
    private function getDummyRepository(): DummyRepository
    {
        return $this->getRepository(DummyEntity::class);
    }


    /**
     * @test
     */
    public function it_should_apply_constraints(): void
    {
        $repository = $this->getDummyRepository();
        /** @var DummyEntity $result */
        $result = $repository->findOneBySpecification(new FieldEquals('id', 1));
        $this->assertEquals(1, $result->getId());
        $collection = $repository->findBySpecification(new FieldEquals('id', 2));
        $this->assertCount(1, $collection);
        $this->assertEquals(2, $collection[0]->getId());
    }

    /**
     * @test
     */
    public function it_should_invoke_query_modifiers(): void
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
    public function it_should_invoke_query_builder_modifiers(): void
    {
        $repository = $this->getDummyRepository();
        $spy = new QueryBuilderModifierSpy();
        $this->assertFalse($spy->wasCalled());
        $repository->findBySpecification($spy);
        $this->assertTrue($spy->wasCalled());
    }

    /**
     * @test
     */
    public function it_should_find_by_an_any_specification(): void
    {
        $any = SpecificationCollection::any(
            new FieldEquals('id', 1),
            new FieldEquals('value', 'second'),
        );

        $repository = $this->getDummyRepository();
        $result = $repository->findBySpecification($any);
        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function it_should_find_by_an_all_specification(): void
    {
        $modifierSpy = new DummyQueryModifier();
        $builderSpy = new QueryBuilderModifierSpy();
        $any = SpecificationCollection::all(
            new FieldEquals('id', 1),
            $modifierSpy,
            $builderSpy,
        );

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
    public function it_can_do_is_not_null_constraints(): void
    {
        $result = $this->getDummyRepository()->findBySpecification(new FieldIsNotNull('value'));

        $this->assertCount(3, $result);
    }

    /**
     * @test
     */
    public function it_should_not_require_constraints_in_collections(): void
    {
        $result = $this->getDummyRepository()->findBySpecification(SpecificationCollection::all());
        $this->assertCount(4, $result);
    }
}
