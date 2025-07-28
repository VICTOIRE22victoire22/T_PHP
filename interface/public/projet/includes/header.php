<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Mon site web">
    <meta name="description" content="desc">
    <meta name="keywords" content="keyword 1">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="1 days">
    <meta name="author" content="<?php echo SITE_AUTHOR; ?>">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo CSS_URL . 'style.css'; ?>">
</head>

<body>
    <header>
        <h1>Mon Site Web</h1>
        <nav>
            <!-- <a href="index.php">Accueil</a>
            <a href="index.php?pg=about">À propos</a>
            <a href="index.php?pg=contact">Contact</a> -->
            <?php
            generate_navbar();
            ?>
        </nav>
    </header>