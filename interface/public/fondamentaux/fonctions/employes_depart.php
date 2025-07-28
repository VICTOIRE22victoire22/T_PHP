<?php
$employes = [
    ['id' => 1, 'nom' => 'Dupont', 'prenom' => 'Alice', 'poste' => 'Développeur', 'salaire' => 3500, 'anciennete' => 3, 'statut' => 'actif'],
    ['id' => 2, 'nom' => 'Martin', 'prenom' => 'Bob', 'poste' => 'Designer', 'salaire' => 3200, 'anciennete' => 5, 'statut' => 'actif'],
    ['id' => 3, 'nom' => 'Bernard', 'prenom' => 'Charlie', 'poste' => 'Manager', 'salaire' => 4500.45, 'anciennete' => 8, 'statut' => 'conge'],
    ['id' => 4, 'nom' => 'Petit', 'prenom' => 'Diana', 'poste' => 'Testeur', 'salaire' => 2800, 'anciennete' => 1, 'statut' => 'actif'],
    ['id' => 5, 'nom' => 'Durand', 'prenom' => 'Eve', 'poste' => 'Chef de projet', 'salaire' => 4000, 'anciennete' => 6, 'statut' => 'inactif']
];

// Salaire moyen
function calculerSalaireMoyen(array $arrs, string $cle): float
{
    $total = 0;
    foreach ($arrs as $arr) {
        // $total = $total + $arr[$cle];
        $total += $arr[$cle];
    }
    return round($total / count($arrs), 2);
}

$salaireMoyen = calculerSalaireMoyen($employes, 'salaire');
echo $salaireMoyen;

// 'actif' => '✅ Actif',
// 'conge' => '🏖️ En congé',
// 'inactif' => '❌ Inactif'

function status(array $arrs, int $id): string
{
    foreach ($arrs as $arr) {
        if ($arr['id'] == $id) {
            switch ($arr['statut']) {
                case "actif":
                    $statut = '✅';
                    break;
                case "conge":
                    $statut = '🏖️';
                    break;
                case "inactif":
                    $statut = '❌';
                default:
                    $statut = "❌";
            }
        }
    }
    return $statut;
}

$statut = status($employes, 3);
echo "Statut : $statut";

// Fonction d'évaluation

// if ($employe['salaire'] > $salaire_moyen)
// $score_performance += 2;
// if ($employe['anciennete'] >= 3)
// $score_performance += 2;
// if ($employe['statut'] == 'actif')
// $score_performance += 1;
// ⭐' : '☆

function score(array $arrs, int $id): string
{
    $score = 0;
    foreach ($arrs as $arr) {
        if ($arr['id'] == $id) {
            $score += ($arr['salaire'] > calculerSalaireMoyen($arrs, 'salaire')) ? 2 : 0;
            $score += ($arr['anciennete']) >= 3 ? 2 : 0;
            $score += ($arr['statut']) === 'actif' ? 1 : 0;

            $stars = '';
            for ($i = 1; $i <= 5; $i++) {
                $stars .= $i <= $score ? '★' : '☆';
            }
        }
    }
    return $stars;
}

echo score($employes, 2);
?>

<body>
    <table>
        <thead>
            <tr>
                <?php
                foreach (array_keys($employes[0]) as $key) {
                    echo "<th>$key</th>";
                }
                echo "<th>Performance</th>";
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($employes as $employe) {
                echo "<tr>";
                echo "<td>$employe[id]</td>";
                echo "<td>$employe[nom]</td>";
                echo "<td>$employe[prenom]</td>";
                echo "<td>$employe[poste]</td>";
                echo "<td>$employe[salaire]</td>";
                echo "<td>$employe[anciennete]</td>";
                echo "<td>" . status($employes, $employe['id']) . "</td>";
                echo "<td>" . score($employes, $employe['id']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>