<?php
error_reporting(E_ALL);

// Inclusion du fichier de configuration
require_once './config/config.php';

// Appels des fonctions
include INCLUDES_PATH . 'functions.php';

// inclusion du fichier header.php
include INCLUDES_PATH . 'header.php';

// Récupération de la valeur du paramètre "pg" contenu dans l'url SI IL EXISTE
$page = isset($_GET['pg']) ? $_GET['pg'] : 'home';
//$page = $_GET['pg'] ?? 'home';

// Recensement des pages autorisées à l'affichage
$allowed_pages = ['home', 'about', 'products', 'product', '404'];

// Vérifier si la page demandée fait partie des pages autorisées
$verified_page = in_array($page, $allowed_pages) ? $page : '404';

include PAGES_PATH . $verified_page . '.php';

// inclusion du fichier header.php
include INCLUDES_PATH . 'footer.php';