<?php
// ===========================
// GESTION DES DONNÉES JSON
// ===========================
$jsonFile = __DIR__ . DIRECTORY_SEPARATOR . "transactions.json";
$transactions = [];
$message = '';

// Charger les transactions existantes
if (file_exists($jsonFile) && filesize($path) > 0) {
    $content = file_get_contents($jsonFile);
    $transactions = json_decode($content, true) ?? [];
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $montant = floatval($_POST['montant']);
    $type = $_POST['type'];
    $compte = $_POST['compte'];

    if ($montant > 0) {
        $transaction = [
            'id' => uniqid(),
            'montant' => $montant,
            'type' => $type,
            'compte' => $compte,
            'date' => date('d/m/Y H:i:s'),
            'timestamp' => time()
        ];

        $transactions[] = $transaction;
        file_put_contents($jsonFile, json_encode($transactions, JSON_PRETTY_PRINT));

        $message = ucfirst($type) . ' de ' . number_format($montant, 2) . ' € ajoutée avec succès !';
    }
}

// Séparer recettes et dépenses
$recettes = array_filter($transactions, fn($t) => $t['type'] === 'recette');
$depenses = array_filter($transactions, fn($t) => $t['type'] === 'depense');

// Calculer les totaux
$totalRecettes = array_sum(array_column($recettes, 'montant'));
$totalDepenses = array_sum(array_column($depenses, 'montant'));
$solde = $totalRecettes - $totalDepenses;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire Bancaire - Version JSON</title>
    <style>
        /* ===========================
           CSS BEM + REM + MOBILE FIRST
        =========================== */
        html {
            font-size: 62.5%; /* 1rem = 10px */
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1.6rem; /* 16px */
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 120rem;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===========================
           HEADER
        =========================== */
        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header__title {
            font-size: 3.2rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 1rem 0;
            text-shadow: 0 0.2rem 0.4rem rgba(0,0,0,0.3);
        }

        .header__subtitle {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.8);
            margin: 0;
        }

        /* ===========================
           SOLDE
        =========================== */
        .solde {
            background: rgba(255,255,255,0.95);
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 3rem;
            text-align: center;
            box-shadow: 0 0.8rem 3.2rem rgba(0,0,0,0.1);
        }

        .solde__label {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .solde__montant {
            font-size: 3.6rem;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }

        .solde__montant--positif {
            color: #27ae60;
        }

        .solde__montant--negatif {
            color: #e74c3c;
        }

        /* ===========================
           FORMULAIRE
        =========================== */
        .form {
            background: rgba(255,255,255,0.95);
            border-radius: 1.5rem;
            padding: 2.5rem;
            margin-bottom: 3rem;
            box-shadow: 0 0.8rem 3.2rem rgba(0,0,0,0.1);
        }

        .form__title {
            font-size: 2.4rem;
            font-weight: 600;
            margin: 0 0 2rem 0;
            color: #2c3e50;
            text-align: center;
        }

        .form__group {
            margin-bottom: 2rem;
        }

        .form__label {
            display: block;
            font-size: 1.4rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 0.8rem;
        }

        .form__input,
        .form__select {
            width: 100%;
            padding: 1.2rem 1.5rem;
            font-size: 1.6rem;
            border: 0.2rem solid #e1e8ed;
            border-radius: 0.8rem;
            background: #fff;
            transition: all 0.3s ease;
        }

        .form__input:focus,
        .form__select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.1);
        }

        .form__radio-group {
            display: flex;
            gap: 2rem;
            margin-top: 0.8rem;
        }

        .form__radio-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .form__radio {
            width: 2rem;
            height: 2rem;
        }

        .form__radio-label {
            font-size: 1.5rem;
            color: #555;
            cursor: pointer;
        }

        .form__button {
            width: 100%;
            padding: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
        }

        .form__button:hover {
            transform: translateY(-0.2rem);
            box-shadow: 0 0.5rem 1.5rem rgba(102, 126, 234, 0.4);
        }

        /* ===========================
           MESSAGE
        =========================== */
        .message {
            background: #d4edda;
            color: #155724;
            padding: 1.5rem;
            border-radius: 0.8rem;
            margin-bottom: 2rem;
            border-left: 0.4rem solid #28a745;
            font-size: 1.4rem;
        }

        /* ===========================
           HISTORIQUE
        =========================== */
        .historique {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
        }

        .historique__colonne {
            background: rgba(255,255,255,0.95);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 0.8rem 3.2rem rgba(0,0,0,0.1);
        }

        .historique__titre {
            font-size: 2rem;
            font-weight: 600;
            margin: 0 0 1.5rem 0;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 0.2rem solid #eee;
        }

        .historique__titre--recettes {
            color: #27ae60;
        }

        .historique__titre--depenses {
            color: #e74c3c;
        }

        .historique__liste {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .historique__item {
            padding: 1.2rem 0;
            border-bottom: 0.1rem solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .historique__item:last-child {
            border-bottom: none;
        }

        .historique__montant {
            font-size: 1.6rem;
            font-weight: 600;
        }

        .historique__montant--recette {
            color: #27ae60;
        }

        .historique__montant--depense {
            color: #e74c3c;
        }

        .historique__details {
            font-size: 1.2rem;
            color: #666;
        }

        .historique__vide {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 2rem;
        }

        .historique__icone {
            margin-right: 0.5rem;
        }

        /* ===========================
           RESPONSIVE DESIGN
        =========================== */
        @media (min-width: 768px) {
            .container {
                padding: 3rem;
            }

            .historique {
                grid-template-columns: 1fr 1fr;
            }

            .form__radio-group {
                justify-content: center;
            }
        }

        @media (min-width: 1024px) {
            .header__title {
                font-size: 4rem;
            }

            .solde__montant {
                font-size: 4.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <header class="header">
            <h1 class="header__title">💰 Gestionnaire Bancaire</h1>
            <p class="header__subtitle">Version 2 : Données JSON • BEM • REM • PHP</p>
        </header>

        <!-- SOLDE -->
        <div class="solde">
            <div class="solde__label">Solde actuel</div>
            <div class="solde__montant <?= $solde >= 0 ? 'solde__montant--positif' : 'solde__montant--negatif' ?>">
                <?= number_format($solde, 2) ?> €
            </div>
        </div>

        <!-- MESSAGE -->
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- FORMULAIRE -->
        <form class="form" method="POST">
            <h2 class="form__title">Nouvelle Transaction</h2>

            <div class="form__group">
                <label class="form__label" for="montant">Montant (€)</label>
                <input class="form__input" type="number" id="montant" name="montant" 
                       min="0.01" step="0.01" required>
            </div>

            <div class="form__group">
                <label class="form__label">Type de transaction</label>
                <div class="form__radio-group">
                    <div class="form__radio-item">
                        <input class="form__radio" type="radio" id="recette" name="type" value="recette" checked>
                        <label class="form__radio-label" for="recette">💰 Recette</label>
                    </div>
                    <div class="form__radio-item">
                        <input class="form__radio" type="radio" id="depense" name="type" value="depense">
                        <label class="form__radio-label" for="depense">💸 Dépense</label>
                    </div>
                </div>
            </div>

            <div class="form__group">
                <label class="form__label" for="compte">Type de compte</label>
                <select class="form__select" id="compte" name="compte" required>
                    <option value="personnel">🧑‍💼 Personnel</option>
                    <option value="professionnel">💼 Professionnel</option>
                </select>
            </div>

            <button class="form__button" type="submit" name="action" value="ajouter">
                Ajouter la transaction
            </button>
        </form>

        <!-- HISTORIQUE -->
        <div class="historique">
            <!-- RECETTES -->
            <div class="historique__colonne">
                <h3 class="historique__titre historique__titre--recettes">
                    💰 Recettes (<?= number_format($totalRecettes, 2) ?> €)
                </h3>
                <ul class="historique__liste">
                    <?php if (empty($recettes)): ?>
                        <li class="historique__vide">Aucune recette enregistrée</li>
                    <?php else: ?>
                        <?php foreach (array_reverse($recettes) as $transaction): ?>
                            <li class="historique__item">
                                <div>
                                    <div class="historique__montant historique__montant--recette">
                                        <span class="historique__icone"><?= $transaction['compte'] === 'professionnel' ? '💼' : '🧑‍💼' ?></span>
                                        +<?= number_format($transaction['montant'], 2) ?> €
                                    </div>
                                    <div class="historique__details">
                                        <?= ucfirst($transaction['compte']) ?> • <?= $transaction['date'] ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- DÉPENSES -->
            <div class="historique__colonne">
                <h3 class="historique__titre historique__titre--depenses">
                    💸 Dépenses (<?= number_format($totalDepenses, 2) ?> €)
                </h3>
                <ul class="historique__liste">
                    <?php if (empty($depenses)): ?>
                        <li class="historique__vide">Aucune dépense enregistrée</li>
                    <?php else: ?>
                        <?php foreach (array_reverse($depenses) as $transaction): ?>
                            <li class="historique__item">
                                <div>
                                    <div class="historique__montant historique__montant--depense">
                                        <span class="historique__icone"><?= $transaction['compte'] === 'professionnel' ? '💼' : '🧑‍💼' ?></span>
                                        -<?= number_format($transaction['montant'], 2) ?> €
                                    </div>
                                    <div class="historique__details">
                                        <?= ucfirst($transaction['compte']) ?> • <?= $transaction['date'] ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>