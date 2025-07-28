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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
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
</body>
</html>