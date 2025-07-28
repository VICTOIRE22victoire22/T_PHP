<?php 

try {
    $count = 10;
    if ($count < 20) {
        throw new Exception("Le compte est inférieur à 20");
    }
    // Code qui peut générer une exception
    throw new Exception("Ceci est une exception");
} catch (Exception $e) {
    // Gestion de l'exception
    echo "Erreur capturée : " . $e->getMessage(). "<br>" 
         . "Avec le code : " . $e->getCode() . "<br>"
         . "Dans le fichier : " . $e->getFile() . "<br>"
         . "À la ligne : " . $e->getLine() . "<br>";
}
finally {
    // Bloc finally (optionnel)
    echo "Bloc finally exécuté.";
}