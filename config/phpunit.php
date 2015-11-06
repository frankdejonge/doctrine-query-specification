<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

include __DIR__.'/entity-manager.php';

function refresh_schema(EntityManager $em)
{
    $classMetadata = $em->getMetadataFactory()->getAllMetadata();
    $schemaTool = new SchemaTool($em);
    $schemaTool->dropDatabase();
    $schemaTool->createSchema($classMetadata);
}