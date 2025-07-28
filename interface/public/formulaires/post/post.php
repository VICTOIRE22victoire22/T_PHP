<?php 

if (isset($_POST['nom']) && isset($_POST['age'])) {
    // Never Trust User Input
    $nom = htmlspecialchars(trim($_POST['nom']));
    $age = (int) $_POST['age'];
    echo "Nom: $nom / Age : $age";
}