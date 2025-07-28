<?php 

// Lecture du fichier cart.json (récupération des données contenues dans le fichier cart.json)
$cart_json_path = __DIR__.DIRECTORY_SEPARATOR.'cart.json';
$cart_json = file_get_contents($cart_json_path );
var_dump($cart_json);

// Convertir les données JSON sous forme de tableaux associatifs
$cart_array = json_decode($cart_json, true);
var_dump($cart_array);

// Calcul du prix total que représenete l'ensemble des articles du panier

// METHODE n°1
function total_price($arr)
{
    $total = 0;
    foreach ($arr as $product) {
        $total += $product['quantite']* $product['prix'];
    }
    return number_format($total,2);
}

$total = total_price($cart_array);
echo $total;


// METHODE n°2 : Avec array_reduce()
function calculerTotal($carry, $item) {
    return $carry + ($item['prix'] * $item['quantite']);
}

$total_panier = array_reduce($cart_array, 'calculerTotal', 0);
echo 'Total du panier : '. $total_panier;


$emojis = [
    'Pomme' => '🍎',
    'Banane' => '🍌',
    'Orange' => '🍊'
];


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier d'achat (chargé depuis JSON)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr td:first-of-type {
            font-size: 30px;
        }
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        .total {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .json {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panier d'achat (chargé depuis JSON)</h1>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_array as $produit): ?>
                    <tr>
                        <td><?= $emojis[htmlspecialchars($produit['nom'])] ?? '' ?></td>
                        <td><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                        <td><?= number_format($produit['quantite']) ?></td>
                        <td><?= number_format($produit['prix'] * $produit['quantite'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total">
                    <td colspan="3">Total panier</td>
                    <td><?= number_format($total, 2, ',', ' ') ?> €</td>
                </tr>
            </tbody>
        </table>
        <h2>Données JSON d'origine</h2>
        <div class="json">
            <?= htmlspecialchars($cart_json) ?>
        </div>
    </div>
</body>
</html>
