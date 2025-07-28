<?php
// Connexion à la base de données
require_once 'sqlite_connexion.php';

try {

    // Données réelles
    $courriel = 'bob@email.com';

    // Création de la requête
    $sql = "SELECT * FROM utilisateurs WHERE email =:mail";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les "vraies valeurs"
    $stmt->bindParam(':mail', $courriel, PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();

    // Chercher le jeu de résultat
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    var_dump($users);

    if (empty($users)) {
        echo "Email absent de la base de données !";
    } else {

        echo "Utilisateur trouvé ! <br>";

        foreach ($users as $user) {
            foreach ($user as $key => $value) {
                echo "$key : $value <br>";
            }
        }
        echo "Les données ont bien été recupérées ! ";
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
