<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des fichiers PDF</title>
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 16px;
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 10px;
            display: block;
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .card-link {
            color: #333;
            text-decoration: none;
        }

        .card-link::before {
            content: "📕";
            font-size: 2em;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>

<?php
// Nom du dossier courant
$nomDossierCourant = basename(__DIR__);

// Chemin relatif universel pour les PDF
$pdfFiles = glob('*.pdf');
?>

<body>
    <h1>Liste des fichiers PDF</h1>
    <div class="card-container">
        <?php foreach ($pdfFiles as $file): ?>
            <div class="card">
                <!-- Lien relatif depuis la racine du site -->
                <a href="/<?php echo htmlspecialchars($nomDossierCourant); ?>/<?php echo htmlspecialchars($file); ?>"
                    class="card-link" target="_blank" rel="noopener">
                    <div class="card-title"><?php echo htmlspecialchars($file); ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>


</html>