<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

include __DIR__.'/entity-manager.php';

return ConsoleRunner::createHelperSet(get_entity_manager());