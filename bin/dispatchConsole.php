<?php
/**
 * The dispatch console is a simple console system for starting, stopping
 * and adding consignment to a dispatch period.
 */

require_once __DIR__ . "/../vendor/autoload.php";

use Commands\StartBatchCommand;
use Commands\AddConsignmentCommand;
use Commands\EndBatchCommand;
use Symfony\Component\Console\Application;

//initiate a new console application
$app = new Application("Dispatch System", "1.0.0");

//add the necessary commands to the application
$app->add(new StartBatchCommand());
$app->add(new AddConsignmentCommand());
$app->add(new EndBatchCommand());

//start the application
$app->run();
