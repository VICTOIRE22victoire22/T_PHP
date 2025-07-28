<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout de Produits</title>
    <style>
        .form {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form__title {
            color: #2c3e50;
            text-align: center;
        }
        .form__form {
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }
        .form__form-group {
            margin-bottom: 15px;
        }
        .form__form-row {
            display: flex;
            gap: 20px;
            align-items: flex-end;
        }
        .form__form-row .form__form-group {
            flex: 1;
            margin-bottom: 0;
        }
        .form__label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form__input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form__btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form__btn:hover {
            background: #218838;
        }
        .form__message {
            color: green;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
        }
        .form__pre {
            background: #fff;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow: auto;
        }
    </style>
</head>


<?php 

$message_nom = $message_prix = $message_qte = '';
$nom_ok = $prix_ok = $qte_ok = false;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['nom'])) {
        $message_nom = "Veuillez remplir votre nom";
    } else {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $nom_ok = true;
    }

    if(empty($_POST['prix'])) {
        $message_prix = "Veuillez saisir un prix";
        $prix='❌';
    } else {
        $prix = (float) number_format(htmlspecialchars(trim($_POST['prix'])),2);
        $prix_ok = true;
    }

    if(empty($_POST['quantite'])) {
        $message_qte = "Veuillez saisir la quantité";
    } else {
        $quantite = (int) htmlspecialchars(trim(($_POST['quantite'])));
        $qte_ok = true;
    }

//echo $message_nom. '<br>'.$message_prix. '<br>'. $message_qte;'<br>';

// echo "Nom :" . $nom . "<br>"; 
// echo "Prix :" . $prix . gettype($prix)."<br>";
// echo "Quantité :" . $quantite .gettype($quantite)."<br>";


if ($nom_ok && $prix_ok && $qte_ok === true) {
// Chemin absolu qui mène au tableau
$path = __DIR__.DIRECTORY_SEPARATOR.'products.json';

// On initialise le tableau de produits
$products = [];

// On vérifie l'existence du fichier products.json
if (file_exists($path)) {
    // on récupère le contenu du fichier
    $content = file_get_contents($path);

    // Conversion du contenu JSON à un format exploitable en PHP (tableau associatif)
    $products = json_decode($content,true);
}
    // Si le tableau de produits n'est pas vide
    if (!empty($products)) {
    $ids = array_column($products, 'id');
    $id = max($ids) + 1;
    // echo "<pre>";
    // var_dump($ids);
    // echo "</pre>";
    } else {
        $id = 1;
    }

// Générer un id de manière automatisée
// $id = $ids[count($ids)];

// Mettre les variables dans un tableau
$new_product = [
    'id'=> $id,
    'nom'=> $nom,
    'prix'=> $prix,
    'qte'=> $quantite
];

// Mettre le nouveau produit dans le tableau de produits
$products[] = $new_product;
var_dump($products);

// Conversion du tableau en JSON
$products_json = json_encode($products, JSON_PRETTY_PRINT);

// Génération du fichier products.json
file_put_contents($path, $products_json, LOCK_EX);
}

}

?>

<main class="main">
    
<h1 class="main__title">Ajouter un produit</h1>
    
<form method="POST" class="form">
        <div class="form__form-row">
            <div class="form__form-group">
                <label class="form__label" for="nom">Nom</label>
                <input class="form__input" type="text" id="nom" name="nom" required>
            </div>
            <div class="form__form-group">
                <label class="form__label" for="prix">Prix</label>
                <input class="form__input" type="number" step="0.01" id="prix" name="prix" required>
            </div>
            <div class="form__form-group">
                <label class="form__label" for="quantite">Quantité</label>
                <input class="form__input" type="number" id="quantite" name="quantite" required>
            </div>
            <button type="submit" class="form__btn">Ajouter</button>
        </div>
    </form>

    <?php
    // Chemin du fichier JSON
$path = __DIR__ . DIRECTORY_SEPARATOR . 'products.json';

    // Vérifier si le fichier existe et n'est pas vide
if (file_exists($path) && filesize($path) > 0) {
    $content = file_get_contents($path);
    $products = json_decode($content, true);

    if (!empty($products)) {
        echo '<h2>Tableau des produits</h2>';
        echo '<table style="width:100%; border-collapse: collapse;">';
        echo '<thead>
                <tr>
                    <th style="border:1px solid #ddd; padding:8px;">Nom</th>
                    <th style="border:1px solid #ddd; padding:8px;">Prix unitaire (€)</th>
                    <th style="border:1px solid #ddd; padding:8px;">Quantité</th>
                    <th style="border:1px solid #ddd; padding:8px;">Total par article (€)</th>
                </tr>
              </thead><tbody>';
        $total_general = 0;
        foreach ($products as $product) {
            $total_article = $product['prix'] * $product['qte'];
            $total_general += $total_article;
            echo '<tr>';
            echo '<td style="border:1px solid #ddd; padding:8px;">' . htmlspecialchars($product['nom']) . '</td>';
            echo '<td style="border:1px solid #ddd; padding:8px;">' . number_format($product['prix'], 2) . '</td>';
            echo '<td style="border:1px solid #ddd; padding:8px;">' . htmlspecialchars($product['qte']) . '</td>';
            echo '<td style="border:1px solid #ddd; padding:8px;">' . number_format($total_article, 2) . '</td>';
            echo '</tr>';
        }
        echo '<tr class="total">
                <td colspan="3" style="text-align:right; font-weight:bold;">Total :</td>
                <td style="font-weight:bold;">' . number_format($total_general, 2) . ' €</td>
              </tr>';
        echo '</tbody></table>';
    } else {
        echo '<p>Aucun produit enregistré.</p>';
    }
} else {
    echo '<p>Aucun produit enregistré.</p>';
}

?>

</main>
</html>
