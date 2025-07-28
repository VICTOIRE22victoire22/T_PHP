<?php

$rates = [
    "EUR" => 1,
    "USD" => 1.15,
    "DZD" => 155.83,
    "THB" => 37.81,
    "BAHT" => 37.55,
    "DONG" => 30000
];

// 1 EUR = 1.15 USD     => 1 USD = (1 / 1.15) EUR
// 1 EUR = 30000 DONG   => 1 DONG = (1 / 30000) EUR
// 1 EUR = xxxxx ???    => 1 ??? = ( 1 / xxxx ) EUR


// 300 USD = 300 * (1 / 1.15) EUR

function convert($amount, $from, $to, $rates) {

// Tranformer la valeur du montant de départ (from) en Euro
$amount_euro = $amount / $rates[$from];

// Calculer la valeur dans la devise finale
$conversion = $amount_euro * $rates[$to];

return $conversion;
} 

$result = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

$amount = $_POST["amount"];
$from = $_POST["fromCurrency"];
$to = $_POST["toCurrency"];

if ($amount <= 0) {
    $result = "Veuillez entrer un montant positif.";
} else {
    $conversion = convert($amount, $from, $to, $rates);
    if ($conversion === null) {
        $result = "Conversion non disponible.";
    } else {
        $conversion = round($conversion, 2);
        $result = "$amount $from = $conversion $to";
    }
}

}


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css">
        <title>Convertisseur</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f5f5;
            }

            .convertisseur {
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            p {
                display: flex;
                justify-content: center;
                /* margin-bottom: 5px; */
                font-weight: bold;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="number"],
            select {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .result {
                display: flex;
                justify-content: center;
                margin-top: 20px;
                font-size: 12px;
                background-color: rgb(205, 201, 201);
                border-radius: 4px;
            }

        </style>
    </head>

    <body>
        <div class="convertisseur">
            <p>Convertisseur de Devises</p>
            <form method="post">
                <label for="amount">Montant:</label>
                <input type="number" step="any" name="amount" id="amount">
                <br>
                <label for="fromCurrency">De:</label>
                <select name="fromCurrency" id="fromCurrency">
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                    <option value="DZD">DZD</option>
                    <option value="THB">THB</option>
                    <option value="DONG">DONG</option>
                </select>
                <br>
                <label for="toCurrency">À:</label>
                <select name="toCurrency" id="toCurrency">
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                    <option value="DZD">DZD</option>
                    <option value="THB">THB</option>
                    <option value="DONG">DONG</option>
                </select>
                <br>
                <input type="submit" value="Convertir">
            </form>
            <div class="result">
            <?php if ($result) echo "<p>$result</p>"; ?>
        </div>
            </div>
        </div>

    </body>

</html>
