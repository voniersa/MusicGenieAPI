<?php

    require_once __DIR__ . '/../src/Application.php';
    require_once __DIR__ . '/../src/Factory.php';
    require_once __DIR__ . '/../src/Page.php';
    require_once __DIR__ . '/../src/Projector.php';
    require_once __DIR__ . '/../src/APIConnector.php';
    require_once __DIR__ . '/../src/Router.php';
    require_once __DIR__ . '/../src/APICreator.php';

    $factory = new Factory();
	$app = $factory->createApplication();
	$app->run();
?>