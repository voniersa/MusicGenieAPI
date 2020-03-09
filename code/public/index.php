<?php

    require_once __DIR__ . '/../src/Application.php';
    require_once __DIR__ . '/../src/Factory.php';
    require_once __DIR__ . '/../src/Page.php';
    require_once __DIR__ . '/../src/Projector.php';
    require_once __DIR__ . '/../src/APIConnector.php';
    require_once __DIR__ . '/../src/Router.php';

    $factory = new Factory(); //Factory-Objekt wird erzeugt
	$app = $factory->createApplication(); //Die Application wird erstellt und zusammengebaut
	$app->run(); //Die Application wird gestartet


    /*class API {
        function Select(){
            $url = "https://api.lyrics.ovh/v1/257ers/Holz";
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            $array = array('Interpret' => '257ers', 'Songtitel' => 'Holz', 'Lyrics' => $data['lyrics']);
            return json_encode($array);
        }
    }

    $API = new API();
    header("Content-type:application/json");
    echo $API->Select();*/
?>