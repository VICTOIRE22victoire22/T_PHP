<?php 

if (isset($_GET['nom']) && isset($_GET['age'])) {
    // Never Trust User Input
    $nom = htmlspecialchars(trim($_GET['nom']));
    $age = (int) $_GET['age'];
    echo "Nom: $nom / Age : $age";
}