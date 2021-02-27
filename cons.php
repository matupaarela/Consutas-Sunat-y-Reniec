<?php
use Peru\Jne\DniFactory;

require 'vendor/autoload.php';

$dni = '29468749';

$factory = new DniFactory();
$cs = $factory->create();

$person = $cs->get($dni);
if (!$person) {
    echo 'Not found';
    return;
}

//var_dump($person);
echo json_encode($person);