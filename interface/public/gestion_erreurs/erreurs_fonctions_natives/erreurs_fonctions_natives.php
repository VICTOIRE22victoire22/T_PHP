<?php
error_reporting(E_ALL); // Activer tous les rapports d'erreurs
ini_set('display_errors', 1); // Afficher les erreurs à l'écran
ini_set('error_log', __DIR__.DIRECTORY_SEPARATOR.'php_errors.log'); // Spécifie le fichier de log

// Définition d'un gestionnaire d'erreurs personnalisé
function monGestionnaireErreur($niveau, $message, $fichier, $ligne) {
    // Logique de gestion des erreurs
    error_log("Erreur [$niveau] : $message dans $fichier à la ligne $ligne");
    // Affiche le message d'erreur dans le navigateur
    echo "<div style='color:red;background:#FFEEEE;padding:10px;'>";
    echo "Erreur personnalisée [$niveau] : $message<br>";
    echo "Fichier : $fichier<br>";
    echo "Ligne : $ligne<br>";
    echo "</div>";
}

// Enregistrement du gestionnaire d'erreurs
set_error_handler('monGestionnaireErreur');


// Exemple de code générant une erreur
echo $variable_non_definie; // E_NOTICE
