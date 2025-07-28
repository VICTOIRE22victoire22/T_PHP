<?php

// Tableaux indexés
$fruits = ['fraise', 'pomme', 'kiwi'];
echo $fruits[1] . "<br>"; // fraise
var_dump($fruits);
echo "<br>";
print_r($fruits);

// Tableaux associatifs
$fruits2 = ['f' => 150, 'p' => 322, 'k' => 56];
echo "Stock de fraises : $fruits2[f]<br>";

// Tableaux multidimenssionnels
$clients = [
    ['prenom' => 'Alice', 'age' => 25, 'dispo' => true],
    ['prenom' => 'Bob', 'age' => 32, 'dispo' => false],
    ['prenom' => 'Charlie', 'age' => 22, 'dispo' => false]
];

// Afficher les informations de Bob
$bobInfos = $clients[1];
$prenom = $bobInfos['prenom'];
$age = $bobInfos['age'];
$dispo = $bobInfos['dispo'];
$statut = $dispo ? "disponible" : "occupé";
echo "$prenom a $age ans et son statut est $statut";

// Ajouter un element
array_push($fruits, 'orange');
// $fruits[] = 'orange';
var_dump($fruits);

// retirer le derier element du tableau
array_pop($fruits);
var_dump($fruits);

// Trier des données de A -> Z 
sort($fruits);
var_dump($fruits);

// Trier des données de Z -> A
rsort($fruits);
var_dump($fruits);

// Boucle foreach sur un tableau indexé
foreach ($fruits as $fruit) {
    echo $fruit . '<br>';
}

// Boucle foreach sur un tableau associatif
foreach ($fruits2 as $key => $value) {
    echo $key . ' : ' . $value . '<br>';
}

// Boucle foreach sur un tableau multidimensionnel
foreach ($clients as $client) {
    echo $client['prenom'] . '-' . $client['age'] . '<br>';
}
