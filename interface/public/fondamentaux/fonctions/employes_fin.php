<?php
$employes = [
    ['id' => 1, 'nom' => 'Dupont', 'prénom' => 'Alice', 'poste' => 'Développeur', 'salaire' => 3500, 'ancienneté' => 3, 'statut' => 'actif'],
    ['id' => 2, 'nom' => 'Martin', 'prénom' => 'Bob', 'poste' => 'Designer', 'salaire' => 3200, 'ancienneté' => 5, 'statut' => 'actif'],
    ['id' => 3, 'nom' => 'Bernard', 'prénom' => 'Charlie', 'poste' => 'Manager', 'salaire' => 4500.45, 'ancienneté' => 8, 'statut' => 'congé'],
    ['id' => 4, 'nom' => 'Petit', 'prénom' => 'Diana', 'poste' => 'Testeur', 'salaire' => 2800, 'ancienneté' => 1, 'statut' => 'actif'],
    ['id' => 5, 'nom' => 'Durand', 'prénom' => 'Eve', 'poste' => 'Chef de projet', 'salaire' => 4000, 'ancienneté' => 6, 'statut' => 'inactif']
];

function calculerSalaireMoyen(array $arrs, string $cle): float
{
    $total = 0;
    foreach ($arrs as $arr) {
        $total += $arr[$cle];
    }
    return round($total / count($arrs), 2);
}

function status(array $arrs, int $id): string
{
    $statut = '';
    foreach ($arrs as $arr) {
        if ($arr['id'] == $id) {
            switch ($arr['statut']) {
                case "actif":
                    $statut = '✅ Actif';
                    break;
                case "congé":
                    $statut = '🏖️ En congé';
                    break;
                case "inactif":
                    $statut = '❌ Inactif';
                    break;
                default:
                    $statut = '❌ Inconnu';
            }
        }
    }
    return $statut;
}

function score(array $arrs, int $id): string
{
    $score = 0;
    $salaireMoyen = calculerSalaireMoyen($arrs, 'salaire');
    foreach ($arrs as $arr) {
        if ($arr['id'] == $id) {
            $score += ($arr['salaire'] > $salaireMoyen) ? 2 : 0;
            $score += ($arr['ancienneté'] >= 3) ? 2 : 0;
            $score += ($arr['statut'] === 'actif') ? 1 : 0;

            $stars = '';
            for ($i = 1; $i <= 5; $i++) {
                $stars .= ($i <= $score) ? '★' : '☆';
            }
            return $stars;
        }
    }
    return '☆☆☆☆☆';
}

function barreAnciennete(int $anciennete): string
{
    $pourcentage = min(($anciennete / 10) * 100, 100);
    return <<<HTML
        <div class="barre-anciennete">
            <div class="progression-anciennete" style="width: {$pourcentage}%;"></div>
        </div>
    HTML;
}

function indicateurSalaire(float $salaire, float $salaire_moyen): string
{
    $classe = $salaire > $salaire_moyen + 500 ? 'salaire-eleve' :
        ($salaire < $salaire_moyen - 500 ? 'salaire-faible' : '');
    $indicateur = $salaire > $salaire_moyen ? '↗️' :
        ($salaire < $salaire_moyen ? '↘️' : '→');
    $couleur = $salaire > $salaire_moyen ? 'green' :
        ($salaire < $salaire_moyen ? 'red' : 'orange');
    return <<<HTML
        <span class="$classe">
            {$salaire}€
            <span style="color: {$couleur};">{$indicateur}</span>
        </span>
    HTML;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tableau des employés</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .barre-anciennete {
            background: #e9ecef;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .progression-anciennete {
            background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);
            height: 100%;
        }

        .salaire-eleve {
            background: #d4edda;
            font-weight: bold;
        }

        .salaire-faible {
            background: #f8d7da;
        }
    </style>
</head>

<body>
    <h1>Tableau des employés</h1>
    <table>
        <thead>
            <tr>
                <?php foreach (array_keys($employes[0]) as $key): ?>
                    <th><?= ucfirst($key) ?></th>
                <?php endforeach; ?>
                <th>Performance</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $emp): ?>
                <tr>
                    <td>
                        <?= $emp['id'] ?>
                    </td>
                    <td>
                        <?= $emp['nom'] ?>
                    </td>
                    <td>
                        <?= $emp['prénom'] ?>
                    </td>
                    <td>
                        <?php echo $emp['poste'] ?>
                    </td>
                    <td>
                        <?php
                        $salaire_moyen = calculerSalaireMoyen($employes, 'salaire');
                        echo indicateurSalaire($emp['salaire'], $salaire_moyen);
                        ?>
                    </td>
                    <td>
                        <?= barreAnciennete($emp['ancienneté']) ?>
                    </td>
                    <td>
                        <?= status($employes, $emp['id']) ?>
                    </td>
                    <td>
                        <?= score($employes, $emp['id']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>