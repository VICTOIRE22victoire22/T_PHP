<?php
session_start();

// Remettre le compteur à zéro
$_SESSION['visit_count'] = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset compteur</title>
</head>
<body>
    <h1>Compteur remis à zéro</h1>
    <p>Le compteur de visites a été remis à zéro.</p>
    <a href="counter.php">Retour au compteur</a>
</body>
</html>