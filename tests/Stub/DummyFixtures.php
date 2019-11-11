<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DummyFixtures implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist(new DummyEntity('first'));
        $manager->persist(new DummyEntity('second'));
        $manager->persist(new DummyEntity('third'));
        $manager->flush();
    }
}
