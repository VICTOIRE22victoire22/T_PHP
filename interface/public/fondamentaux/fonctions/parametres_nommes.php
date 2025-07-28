<?php

function afficher($prenom, $nom = "inconnu", $age = 0): string
{
    return "$prenom $nom a $age ans.";
}

// Méthode n°1
echo afficher(age: 15, prenom: 'Marie', nom: 'Durand');

// Méthode n°2
$message = afficher(age: 15, prenom: 'Marie', nom: 'Durand');
echo $message;

// Sans paramètres nommés
$message2 = afficher(15, 'Marie', 'Durand');
echo $message2; // 15 Marie a Durand ans.