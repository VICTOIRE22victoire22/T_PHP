<?php
// =========== TRANSACTION ENGEGISTREES DANS UN FICHIER =========

function display_transactions($transactions, $recipes, $expenses)
{
    $config = [
        'recette' => [
            'title' => '💰 Recettes',
            'icon' => '💼',
            'sign' => '+',
            'amount' => $recipes,
            'class' => 'content-recipes'
        ],
        'depense' => [
            'title' => '💸 Depense',
            'icon' => '🧑‍💼',
            'sign' => '-',
            'amount' => $expenses,
            'class' => 'content-expenses'
        ]
    ];

    foreach ($config as $type => $settings) {
        echo '<div class="' . $settings['class'] . '">';
        echo '<h2>' . $settings['title'] . ' ' . number_format($settings['amount'], 2, ',', ' ') . ' €</h2>';
        echo '<p>';

        foreach ($transactions as $transaction) {
            if ($transaction['type'] === $type) {
                echo $settings['icon'] . ' ' . $settings['sign'] . $transaction['montant'] . '€<br>';
                echo $transaction['compte'] . ' ' . $transaction['date'] . '<br>';
            }
        }

        echo '</p>';
        echo '</div>';
    }
}

// création du chemin
$path = __DIR__ . DIRECTORY_SEPARATOR . 'transactions.json';

//tableau
$transactions = [];

// Charger les transactions présentes dans le fichier json
if (file_exists($path) && filesize($path) > 0) {
    $content = file_get_contents($path);
    $transactions = json_decode($content, true);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // générer l'id de manière automatisée
    if (!empty($transactions)) {
        $ids = array_column($transactions, 'id');
        $id = max($ids) + 1;
    } else {
        $id = 1;
    }

    // Mettre les variables dans un tableau
    $day = date("d.m.y g:i:s");
    $montant = round((float) $_POST['montant'], 2);
    $type = $_POST['type'];
    $compte = $_POST['compte'];

    $new_transactions = [
        'id' => $id,
        'montant' => $montant,
        'type' => $type,
        'compte' => $compte,
        'date' => $day,
        'timestamp' => 1,
    ];

    // Mettre le nouveau transaction dans le tableau 
    $transactions[] = $new_transactions;

    // Conversion du tableau en JSON
    $transactions_json = json_encode($transactions, JSON_PRETTY_PRINT);

    // Génération du fichier 
    file_put_contents($path, $transactions_json, LOCK_EX);
}

// =========== CALCUL DU SOLDE INITIAL AVEC TOUTES LES TRANSACTIONS =========

// (Re)charger toutes les transactions après ajout éventuel
if (file_exists($path) && filesize($path) > 0) {
    $content = file_get_contents($path);
    $transactions = json_decode($content, true);
}

// Initialiser les variables
$recipes = 0;
$expenses = 0;
$current_balance = 0;

// Calcul des totaux avec l'ensemble des transactions
if (!empty($transactions)) {
    foreach ($transactions as $transaction) {
        if ($transaction['type'] === 'recette') {
            $recipes += $transaction['montant'];
        } elseif ($transaction['type'] === 'depense') {
            $expenses += $transaction['montant'];
        }
    }
    $current_balance = $recipes - $expenses;
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

        main {
            background-color: #6C79D8;
        }

        h1 {
            color: white;
            text-align: center;
            padding: 10px;
        }

        .balance {
            background-color: #f5f5f5;
            text-align: center;
            border-radius: 20px;
            margin: 20px 20%;
            padding: 10px;
        }

        .balance span {
            color: #5AAC71;
            font-size: 30px;
        }

        .content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .content-recipes,
        .content-expenses {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .content-recipes h2 {
            text-align: center;
            color: #5AAC71;
        }

        .content-expenses h2 {
            text-align: center;
            color: #CD5473;
        }
    </style>
</head>

<body>
    <main>
        <h1> 💰 Gestionnaire Bancaire </h1>
        <div class="balance">
            <p> Solde actuel </p>
            <span><?= number_format($current_balance, 2, ',', ' ') ?> €</span>
        </div>

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

        <article class="content">
            <?php
            display_transactions($transactions, $recipes, $expenses);
            ?>
        </article>
    </main>

</body>

</html>