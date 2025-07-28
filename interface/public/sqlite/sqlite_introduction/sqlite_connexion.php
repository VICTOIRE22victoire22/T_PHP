<?php

try { 
    $path = __DIR__.DIRECTORY_SEPARATOR.'database.sqlite';
    
    // Connexion à la base de données database.sqlite
    $pdo = new PDO('sqlite:'.$path);  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    
    // Création de la requête SQL
    $sql = "CREATE TABLE IF NOT EXISTS utilisateurs (  
        id INTEGER PRIMARY KEY AUTOINCREMENT,  
        nom TEXT NOT NULL,  
        email TEXT NOT NULL  
    );";

    // Exécution de la requête
    $pdo -> exec($sql);
} catch (PDOException $e) {  
    die('Erreur : ' . $e->getMessage());  
} 