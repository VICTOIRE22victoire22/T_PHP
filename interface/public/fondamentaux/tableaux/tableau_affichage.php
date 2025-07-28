<?php
$commandes = [
    ['id' => 1, 'client' => 'Dupont', 'montant' => 150.00, 'date' => '2024-01-15', 'statut' => 'livré'],
    ['id' => 2, 'client' => 'Martin', 'montant' => 75.50, 'date' => '2024-01-16', 'statut' => 'en cours'],
    ['id' => 3, 'client' => 'Bernard', 'montant' => 220.00, 'date' => '2024-01-17', 'statut' => 'annulé']
];



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau des commandes</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .delivered {
            background-color: #b3ffb3;
        }

        .pending {
            background-color: #ffffb3;
        }

        .cancelled {
            background-color: #ffb3b3;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <!-- <th>Id</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Statut</th> -->
                <?php
                foreach (array_keys($commandes[0]) as $key) {
                    echo "<th>$key</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // foreach ($commandes as $commande) {
            //     echo "<tr>";
            //     foreach ($commande as $key => $value) {
            //         echo "<td>$value</td>";
            //     }
            //     echo "</tr>";
            // }
            ?>

            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <?php foreach ($commande as $key => $value): ?>
                        <?php
                        $className = ($key == 'statut') ? match ($value) {
                            'livré' => 'delivered',
                            'en cours' => 'pending',
                            'annulé' => 'cancelled',
                            default => ''
                        } : 'default';
                        ?>
                        <td class="<?= $className; ?>"><?= $value; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>