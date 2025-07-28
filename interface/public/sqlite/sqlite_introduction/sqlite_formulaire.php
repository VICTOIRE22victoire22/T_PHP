<?php
// Connexion à la base de données
require_once 'sqlite_connexion.php';

try {
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupration des données utilisateur
    $prenom = $_POST['firstname'];
    $courriel= $_POST['email'];

    // Création de la requête
    $sql = "INSERT INTO utilisateurs (nom, email) VALUES (:name, :mail)";
    
    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les "vraies valeurs"
    $stmt->bindParam(':name',$prenom,PDO::PARAM_STR);
    $stmt->bindParam(':mail',$courriel,PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();
}
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLITE_INSERTION</title>
</head>

<body>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method='POST'>
        <label for="name">Prénom:</label><br>
        <input type="text" id="name" name="firstname"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Enregister dans la base">
    </form>
</body>

</html>