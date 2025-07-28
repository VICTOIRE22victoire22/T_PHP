<?php
session_start();

// Vérifier si la variable visit_count existe
if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 1;
    $message = "Bienvenue ! C'est votre première visite.";
} else {
    $_SESSION['visit_count']++;
    $message = "Bon retour !";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compteur de visites</title>
</head>
<body>
    <h1>Compteur de visites</h1>
    <p><?php echo $message; ?></p>
    <p><strong>Nombre de visites :</strong> <?php echo $_SESSION['visit_count']; ?></p>

    <br>
    <a href="counter.php">Actualiser la page</a> |
    <a href="reset_counter.php">Remettre à zéro</a>
</body>
</html>