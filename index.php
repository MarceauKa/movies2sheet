<?php

use Symfony\Component\Console\Application;
use App\Commands;

require 'vendor/autoload.php';

$application = new Application();

$application->add(new Commands\Overview);
$application->add(new Commands\Debug);
$application->add(new Commands\Database);
$application->add(new Commands\Ffprobe);
$application->add(new Commands\Tmdb);

$application->run();
