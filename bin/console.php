<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Symfony\Component\Console\Application();

$app->add(new \Luky\Console\DummyCommand());


exit($app->run());
