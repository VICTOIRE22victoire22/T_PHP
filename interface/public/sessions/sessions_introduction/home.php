<?php
session_start();

// Si la variable de session $_SESSION['username']
if (isset($_SESSION['username'])) {
echo "<h2>Bienvenue, $_SESSION[username] !</h2>";
echo "<p><a href='logout.php'>Se déconnecter</a></p>";
} else {
//    echo "Connectez-vous pour accéder à cette page !<br>";
//    echo "<a href='login.php'>Connexion</a>";
$_SESSION['message'] = 'Connectez-vous pour continuer votre parcours !'; 
header("Location: login.php");
exit();
}