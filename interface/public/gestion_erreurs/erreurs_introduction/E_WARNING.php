<?php
echo "Début du script";

// Division par zéro
$result = 10 / 0;
echo "Résultat: $result";

// Fichier inexistant
$file = fopen("fichier_inexistant.txt", "r");

// Include d'un fichier inexistant
include "fichier_inexistant.php";

echo "Fin du script";
?>