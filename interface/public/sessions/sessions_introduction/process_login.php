<?php
session_start();

$users = [
    'alex'=> '12345',
    'sophie'=> '789'
];

// Récupération des données utilisateurs 
$username  = $_POST['username'];
$password  = $_POST['password'];

//echo if($users['alix'];

if(isset($users[$username]) && $users[$username] === $password) {
    echo "ok";
    // Création d'une variable de session
    $_SESSION['username'] = $username;
    echo "ok";
    // Redirection vers la page home.php
    header("Location: home.php");
    exit();
} 
else {
    echo "Nom d'utilisateur ou Mot de passe incorrect(s)";
}