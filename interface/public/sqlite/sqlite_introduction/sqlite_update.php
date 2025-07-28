<?php
// Connexion à la base de données
require_once 'sqlite_connexion.php';

try {

    // Données réelles
    $id = 2;
    $courriel= 'bob@email.com';

    // Création de la requête
    $sql = "UPDATE utilisateurs SET email = :mail  WHERE id =:id";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les "vraies valeurs"
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':mail', $courriel, PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();

    echo "Les données ont bien été mises à jour ! ";
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
