<?php

use App\Command;

require_once __DIR__ . '/vendor/autoload.php';

// Unset unneeded CLI parameters we will not be using.
unset($argv[0]);
$parameters = array_values($argv);

$command = new Command($parameters);
$result = $command->run();
echo "\n" . $result . "\n";
