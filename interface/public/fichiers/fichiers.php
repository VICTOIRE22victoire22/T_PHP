<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des clients</title>
    <style>
        .customers {
            width: 80%;
            margin: 2rem auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            font-family: 'Arial', sans-serif;
        }

        .customers__header {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .customers__cell,
        .customers__header {
            padding: 15px 20px;
        }

        .customers__row {
            border-bottom: 1px solid #dddddd;
        }

        .customers__row--even {
            background-color: #f3f3f3;
        }

        .customers__row--last {
            border-bottom: 2px solid #009879;
        }

        .customers__row:hover {
            background-color: #e0f7fa;
            cursor: pointer;
        }

        .customers__no-data {
            text-align: center;
            color: #ff0000;
            padding: 20px;
            font-size: 1.2em;
        }
    </style>
</head>

<?php

// Donnée initiale
$data = 'alice|alice@email.com';        
// $data = 'bob|bob@email.com';
// $data = '<script>alert("coucou");</script>|emil';

$path = __DIR__ . DIRECTORY_SEPARATOR . 'data.txt';
//echo $path;

// Ecriture dans le fichiers data.txt
file_put_contents($path, $data . PHP_EOL, FILE_APPEND | LOCK_EX);

// Récupération du contenu du fichier data.txt
$customers = file($path);

// var_dump($customers);

// Affichage des données du premier client du fichier
$client1 = $customers[0];

// Transformation de la chaine en tableau
$client1_arr = explode('|', $client1);

// Affichage du tableau
//var_dump($client1_arr);
?>

<table class="customers">
    <thead class="customers__header">
        <tr class="customers__row">
            <th class="customers__header">Prénom</th>
            <th class="customers__header">Email</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($customers as $index => $customer):
            $client = explode('|', trim($customer));
            list($firstname, $email) = $client;
            ?>
            <tr>
                <td class="customers__cell"><?= htmlspecialchars($firstname); ?></td>
                <td class="customers__cell"><?= htmlspecialchars($email); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>

</html>