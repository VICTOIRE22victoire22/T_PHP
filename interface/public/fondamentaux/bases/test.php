<?= "test" ?>

<?php

echo nl2br("Un \n deux \n");

$prenom = "Alice";
$nom = "ECILA";

echo $prenom . ' ' . $nom . '<br>';
echo "$prenom $nom <br>";

define('PI', 3.14);

echo 'Pi est égale à ' . PI . '<br>';

echo 'Cette instruction est à la ligne ' . __LINE__ . '<br>';
echo 'Ce fichier est : ' . __FILE__ . '<br>';
echo 'Ce fichier est situé" dans le dossier : ' . __DIR__ . '<br>';

