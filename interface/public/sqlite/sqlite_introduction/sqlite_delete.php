<?php

require_once 'sqlite_connexion.php';

try {  

    $id = 5;

    //Création de la requête d'insertion
    $sql = 'DELETE FROM utilisateurs WHERE id = :id';

    //Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les valeurs
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    //Exécution de la requête
    $stmt->execute();
    
    echo "Données supprimées avec succès";

} catch (PDOException $e) {  
    die('Erreur : ' . $e->getMessage());  
} 

?>