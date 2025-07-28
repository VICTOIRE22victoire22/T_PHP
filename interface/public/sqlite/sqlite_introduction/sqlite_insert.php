<?php
// https://sharemycode.fr/bu5 

try {
    $path = __DIR__ . DIRECTORY_SEPARATOR . 'database.sqlite';

    // Connexion à la base de données database.sqlite
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Données réelles
    $prenom = 'Alice';
    $courriel = 'alice@email.fr';
    
    // Création de la requête
    $sql = "INSERT INTO utilisateurs (nom, email) VALUES (:name, :mail)";
    
    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les "vraies valeurs"
    $stmt->bindParam(':name',$prenom,PDO::PARAM_STR);
    $stmt->bindParam(':mail',$courriel,PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();

    echo "Les données ont bien été insérées ! ";
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
