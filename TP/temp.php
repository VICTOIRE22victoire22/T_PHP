<div class="content-recipes">
    <h2> 💰 Recettes <?= number_format($recipes, 2, ',', ' ') ?> € </h2>
    <p> <?php
        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'recette') {
                echo '💼' . ' ' . '+' . $transaction['montant'] . '' .  '€' . '<br>';
                echo $transaction['compte'] . ' ' . $transaction['date'] . '<br>';
            }
        }
        ?> </p>
</div>

<div class="content-expenses">
    <h2> 💸 Depense <?= number_format($expenses, 2, ',', ' ') ?> € </h2>
    <p> <?php
        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'depense') {
                echo '🧑‍💼' . ' ' . '-' . $transaction['montant'] . '' . '€' . '<br>';
                echo $transaction['compte'] .  ' ' . $transaction['date'] . '<br>';
            }
        }
        ?> </p>
</div>
