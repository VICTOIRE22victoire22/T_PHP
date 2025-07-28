<?php
$employes = [
    ['nom' => 'Dupont', 'prenom' => 'Alice', 'poste' => 'Développeur', 'salaire' => 3500, 'anciennete' => 3, 'statut' => 'actif'],
    ['nom' => 'Martin', 'prenom' => 'Bob', 'poste' => 'Designer', 'salaire' => 3200, 'anciennete' => 5, 'statut' => 'actif'],
    ['nom' => 'Bernard', 'prenom' => 'Charlie', 'poste' => 'Manager', 'salaire' => 4500, 'anciennete' => 8, 'statut' => 'conge'],
    ['nom' => 'Petit', 'prenom' => 'Diana', 'poste' => 'Testeur', 'salaire' => 2800, 'anciennete' => 1, 'statut' => 'actif'],
    ['nom' => 'Durand', 'prenom' => 'Eve', 'poste' => 'Chef de projet', 'salaire' => 4000, 'anciennete' => 6, 'statut' => 'inactif']
];

// Calcul du salaire moyen avec array_column et array_sum uniquement
$salaire_moyen = array_sum(array_column($employes, 'salaire')) / count($employes);
$anciennete_moyenne = array_sum(array_column($employes, 'anciennete')) / count($employes);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau des employés</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
            position: sticky;
            top: 0;
        }

        .ligne-paire {
            background: #f8f9fa;
        }

        .ligne-impaire {
            background: white;
        }

        .salaire-eleve {
            background: #d4edda;
            font-weight: bold;
        }

        .salaire-faible {
            background: #f8d7da;
        }

        .anciennete-senior {
            color: #6f42c1;
            font-weight: bold;
        }

        .anciennete-junior {
            color: #fd7e14;
        }

        .statut-actif {
            background: #28a745;
            color: white;
        }

        .statut-conge {
            background: #ffc107;
            color: black;
        }

        .statut-inactif {
            background: #dc3545;
            color: white;
        }

        .statut {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .barre-anciennete {
            background: #e9ecef;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progression-anciennete {
            background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);
            height: 100%;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body>
    <h1>👥 Gestion des Employés</h1>
    <div style="background: #e9ecef; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <strong>📊 Statistiques :</strong>
        Total employés : <?= count($employes) ?> |
        Salaire moyen : <?= number_format($salaire_moyen, 0, ',', ' ') ?>€ |
        Ancienneté moyenne : <?= round($anciennete_moyenne, 1) ?> ans
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <?php foreach (array_keys($employes[0]) as $key): ?>
                    <th><?= ucfirst($key) ?></th>
                <?php endforeach; ?>
                <th>Nom complet</th>
                <th>Barre ancienneté</th>
                <th>Statut</th>
                <th>Salaire (indicateur)</th>
                <th>Performance</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $index => $employe): ?>
                <?php
                $classe_ligne = ($index % 2 == 0) ? 'ligne-paire' : 'ligne-impaire';
                $classe_salaire = $employe['salaire'] > $salaire_moyen + 500 ? 'salaire-eleve' :
                    ($employe['salaire'] < $salaire_moyen - 500 ? 'salaire-faible' : '');
                $classe_anciennete = $employe['anciennete'] >= 5 ? 'anciennete-senior' :
                    ($employe['anciennete'] <= 2 ? 'anciennete-junior' : '');
                $pourcentage_anciennete = min(($employe['anciennete'] / 10) * 100, 100);
                // Calcul du score de performance sans fonction
                $score_performance = 0;
                if ($employe['salaire'] > $salaire_moyen)
                    $score_performance += 2;
                if ($employe['anciennete'] >= 3)
                    $score_performance += 2;
                if ($employe['statut'] == 'actif')
                    $score_performance += 1;
                ?>
                <tr class="<?= $classe_ligne ?>">
                    <td><strong><?= $index + 1 ?></strong></td>
                    <?php foreach ($employe as $key => $value): ?>
                        <td><?= $value ?></td>
                    <?php endforeach; ?>
                    <td><strong><?= $employe['nom'] ?></strong> <?= $employe['prenom'] ?></td>
                    <td>
                        <div class="barre-anciennete">
                            <div class="progression-anciennete" style="width: <?= $pourcentage_anciennete ?>%;"></div>
                        </div>
                    </td>
                    <td>
                        <span class="statut statut-<?= $employe['statut'] ?>">
                            <?php
                            echo match ($employe['statut']) {
                                'actif' => '✅ Actif',
                                'conge' => '🏖️ En congé',
                                'inactif' => '❌ Inactif'
                            };
                            ?>
                        </span>
                    </td>
                    <td class="<?= $classe_salaire ?>">
                        <?= number_format($employe['salaire'], 0, ',', ' ') ?>€
                        <?php if ($employe['salaire'] > $salaire_moyen): ?>
                            <span style="color: green;">↗️</span>
                        <?php elseif ($employe['salaire'] < $salaire_moyen): ?>
                            <span style="color: red;">↘️</span>
                        <?php else: ?>
                            <span style="color: orange;">→</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $score_performance ? '⭐' : '☆' ?>
                        <?php endfor; ?>
                        <small>(<?= $score_performance ?>/5)</small>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 20px; font-size: 14px;">
        <h3>📋 Légende :</h3>
        <ul>
            <li><span class="salaire-eleve" style="padding: 2px 4px;">Salaire élevé</span> : >
                <?= number_format($salaire_moyen + 500, 0, ',', ' ') ?>€
            </li>
            <li><span class="salaire-faible" style="padding: 2px 4px;">Salaire faible</span> : < <?= number_format($salaire_moyen - 500, 0, ',', ' ') ?>€</li>
            <li><span class="anciennete-senior">Sénior</span> : ≥ 5 ans | <span class="anciennete-junior">Junior</span>
                : ≤ 2 ans</li>
            <li>Performance : Salaire + Ancienneté + Statut actif</li>
        </ul>
    </div>
</body>

</html>