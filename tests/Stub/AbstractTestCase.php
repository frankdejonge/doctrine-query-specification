<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationAwareRepository;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp(): void
    {
        $this->em = get_entity_manager();
        refresh_schema($this->em);
    }

    public function loadFixtures(array $fixtures): void
    {
        $loader = new Loader();

        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @param string $className
     *
     * @return SpecificationAwareRepository
     */
    public function getRepository($className): SpecificationAwareRepository
    {
        return $this->em->getRepository($className);
    }
}
