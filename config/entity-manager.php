<?php

include __DIR__.'/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [__DIR__.'/../tests'];
$isDevMode = true;
$dbParams = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/test.db',
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$em = EntityManager::create($dbParams, $config);

function get_entity_manager()
{
    global $em;

    return $em;
}