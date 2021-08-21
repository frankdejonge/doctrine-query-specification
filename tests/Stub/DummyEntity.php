<?php

declare(strict_types=1);

namespace FrankDeJonge\DoctrineQuerySpecification\Tests\Stub;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DummyRepository")
 * @ORM\Table(name="dummy_items")
 */
class DummyEntity
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
