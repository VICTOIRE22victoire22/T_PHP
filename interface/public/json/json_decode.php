<?php 

// Données au format JSON
$json = '{"firstname":"Alice","email":"alice@email.com","age":25}';

// Récupération des données sous forme de tableau associatif
$data = json_decode($json, true);

var_dump($data);
// array(3) {
//     ["firstname"]=>
//     string(5) "Alice"
//     ["email"]=>
//     string(15) "alice@email.com"
//     ["age"]=>
//     int(25)
//   }
  
// Affichage du prénom
echo $data['fistname']; // Alice