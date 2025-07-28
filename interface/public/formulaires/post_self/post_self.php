<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire POST_SELF</title>
    <link rel="stylesheet" href="./formulaire.css">
    <style>
        .error {
    color: red;
    font-size: 0.8em;
    margin-top: 0.25em;
}

    </style>
</head>
<body>

<?php
$messageNom = '';
$messageAge = '';
$nom = '';
$age = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du nom
    if (!isset($_POST['nom']) && empty($_POST['nom'])) {
        $messageNom = '<div class="error">Veuillez indiquer votre nom !</div>';
    } else {
        $nom = htmlspecialchars(trim($_POST['nom']));
    }

    // Traitement de l'âge
    if (empty($_POST['age'])) {
        $messageAge = '<div class="error">Veuillez indiquer votre âge !</div>';
    } else {
        $age = (int) $_POST['age'];
    }
}
?>

    <form class="form" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <div class="form__group">
            <label class="form__label" for="nom">Nom</label>
            <input class="form__input" type="text" id="nom" name="nom" placeholder="Votre nom" value="<?= $nom; ?>">
            <?= $messageNom; ?>
        </div>
        <div class="form__group">
            <label class="form__label" for="age">Âge</label>
            <input class="form__input" type="number" id="age" name="age" value="<?= $age; ?>">
            <?= $messageAge; ?>
        </div>
        <button class="form__submit" type="submit">Envoyer</button>
    </form>
</body>
</html>
