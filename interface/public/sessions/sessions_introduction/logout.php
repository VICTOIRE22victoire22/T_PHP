<?php 
session_start();

// Détruire le session
session_destroy();

// Redirection vers la page de connexion
header("Location: login.php?msg=1");
exit();