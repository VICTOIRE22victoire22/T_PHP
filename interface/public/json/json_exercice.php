<?php 

$data = [
    'firstname' => 'Alice',
    'email'=>'alice@email.com',
    'age'=> 25
];

// $data => json => placer les données dans un fichier json.txt

// Transformation d'un tableau en JSON
$json = json_encode($data, JSON_PRETTY_PRINT);

// Chemin absolu qui mène au fichier
$path = __DIR__ . DIRECTORY_SEPARATOR . 'json.txt';

// Ecriture dans le fichier
file_put_contents($path, $json. PHP_EOL, FILE_APPEND|LOCK_EX);

