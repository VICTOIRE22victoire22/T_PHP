<?php

$articles = [
    ["pc" => 399],
    ["tablette" => 189],
    ["montre" => 89],
    ["telephone" => 239],
    ["disque dur" => 98],
];

$prix_reference = 190;
$compteur = 0;

// Afficher le nombre d'élements dont le prix est inférieur au prix de réference à l'aide du spaceshift operator.

foreach ($articles as $article) {
    foreach ($article as $key => $value) {
        (($value <=> $prix_reference) === -1) ? $compteur++ : null;
    }
}

echo "Il y a $compteur articles dont le prix est inférieur à $prix_reference €.";