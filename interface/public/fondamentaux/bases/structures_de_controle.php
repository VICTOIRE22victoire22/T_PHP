<?php

$apple = false;
$pear = false;

if ($apple) {
    echo "Compote de pomme <br>";
} elseif ($pear) {
    echo "Tarte aux poires <br>";
} else {
    echo "Bananes flambées <br>";
}

// IF : syntaxe alternative
$age = 15;
if ($age > 18): ?>
    <p>Vous avez le droit de voter.</p>
<?php else: ?>
    <p>Vous n'avez pas le droit de voter.</p>
<?php endif; ?>

<?php
// ternaire
echo $apple === true ? "Compote de pomme" : "Tarte aux poires";

// Switch

$jour = "lundi";

switch ($jour) {
    case "lundi":
        echo 'Début de semaine';
        break;
    case "vendredi":
        echo 'Fin de semaine';
        break;
    default:
        echo "Week-end";
}

// match (PHP >= 8.0)

$langage = 'php';

$projet = match ($langage) {
    'php' => 'Symfony',
    'javascript' => 'React',
    'python' => 'Django',
};

echo "Le langage $langage utilise le Framework $projet";