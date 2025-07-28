<?php

// Récupération du chemin du fichier json
$path = __DIR__ . DIRECTORY_SEPARATOR . "transactions.json";

//initialisation d'un tableau vide
$transactions = [];

// initialisation des variables contenant le solde, les recettes et les dépenses
$balance = 0;
$receipt = 0;
$expense = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Récupération dans des variables des valeurs de chaque input 
    $amount = $_POST['montant'];
    $type = $_POST['type'];
    $account = $_POST['compte'];
    $transaction_date = date("d.m.y H: i:s");


    if (file_exists($path)) {
        $content = file_get_contents($path);
        // Conversion du tableau du format JSON au format php
        $transactions = json_decode($content, true);
    }

    // Si le tableau n'est pas vide on créer un id pour chaque transaction sinon l'id est initialisé à 1.
    if (!empty($transactions)) {
        $ids = array_column($transactions, 'id');
        $id = max($ids) + 1;
    } else {
        $id = 1;
    }

    $new_transactions = [
        'id' => $id,
        'montant' => $amount,
        'type' => $type,
        'compte' => $account,
        'date' => $transaction_date
    ];

    //Ajout de la nouvelle transaction dans le tableau transactions.json
    $transactions[] = $new_transactions;

    // Conversion du tableau au format json
    $transactions_json = json_encode($transactions, JSON_PRETTY_PRINT);

    // Génération du fichier transactions.json
    file_put_contents($path, $transactions_json, LOCK_EX);
}

if (file_exists($path) && filesize($path) > 0) {
    $content = file_get_contents($path);
    $transactions = json_decode($content, true);

    $balance = 0;
    $receipt = 0;
    $expense = 0;

    foreach ($transactions as $transaction) {
        if ($transaction['type'] === 'recette') {
            $receipt += $transaction['montant'];
        } else {
            $expense += $transaction['montant'];
        }
        $balance = $receipt - $expense;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Transaction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        main {
            background: linear-gradient(to left, #697CE2, #6C6CCB);
        }

        h1 {
            color: #f5f5f5;
            text-align: center;
            font-size: 24px;
        }

        .solde {
            text-align: center;
            background-color: #f5f5f5;
            border-radius: 25px;
            margin-top: 25px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 25px;
            max-width: 500px;
            padding: 15px;
        }

        .solde_info {
            color: #36AE64;
            font-size: 24px;
        }

        .form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-item input[type="radio"] {
            width: auto;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn:hover {
            background: #0056b3;
        }

        .historique_container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
            gap: 20px;
            flex-wrap: wrap;
            padding: 30px;
            margin-top: 25px;
            max-width: 800px;
        }

        .historique_title {
            font-size: 20px;
        }

        .historique_recette,
        .historique_depense {
            background-color: #f5f5f5;
            width: 300px;
            align-self: center;
        }

        .historique_recette {
            color: #36AE64;
        }

        .historique_depense {
            color: #E34A44;
        }
    </style>
</head>

<body>
    <main>
        <h1>💰 Gestionnaire bancaire</h1>
        <section class="solde">
            <p>Solde Actuel :</p>
            <p class="solde_info">
                <?php
                if (isset($balance)) {
                    echo number_format($balance, 2, '.', ',');
                }
                ?>
            </p>
        </section>

        <form class="form" method="POST">
            <h2>Nouvelle Transaction</h2>

            <div class="form-group">
                <label for="montant">Montant (€)</label>
                <input type="number" id="montant" name="montant" min="0.01" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Type de transaction</label>
                <div class="radio-group">
                    <div class="radio-item">
                        <input type="radio" id="recette" name="type" value="recette" checked>
                        <label for="recette">💰 Recette</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="depense" name="type" value="depense">
                        <label for="depense">💸 Dépense</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="compte">Type de compte</label>
                <select id="compte" name="compte" required>
                    <option value="personnel">🧑‍💼 Personnel</option>
                    <option value="professionnel">💼 Professionnel</option>
                </select>
            </div>

            <button class="btn" type="submit" name="action" value="ajouter">
                Ajouter la transaction
            </button>
        </form>
        <div class="historique_container">
            <section class="historique_recette">
                <h2 class="historique_title">Recettes : <?= $receipt ?> </h2>
                <?php
                if (!empty($transactions)) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['type'] === 'recette') {
                            echo "<hr>";
                            echo "<p>" . "💼" . " " . "+ " . $transaction['montant'] . " €" . "</p>";
                            echo "<span>" . $transaction['compte'] . " / " . $transaction['date'] . "</span>";
                        }
                    }
                }
                ?>
            </section>
            <section class="historique_depense">
                <h2 class="historique_title">Dépenses : <?= $expense ?> </h2>
                <?php
                if (!empty($transactions)) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['type'] === 'depense') {
                            echo "<hr>";
                            echo "<p>" . "🧑‍💼" . " " . "- " . $transaction['montant'] . " €" . "</p>";
                            echo "<span>" . $transaction['compte'] . " / " . $transaction['date'] . "</span>";
                        }
                    }
                }
                ?>
            </section>
        </div>

    </main>
</body>

</html>