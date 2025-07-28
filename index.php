<?php
$wwwPath = dirname(__FILE__);
$projets = [];
if ($handle = opendir($wwwPath)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && is_dir($wwwPath . DIRECTORY_SEPARATOR . $entry) && $entry != basename(__DIR__)) {
            $projets[] = $entry;
        }
    }
    closedir($handle);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>RACINE</title>
    <style>
        /* Block: page */
        .page {
            background: #f5f7fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .page__main {
            max-width: 80%;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page__title {
            text-align: center;
            font-size: 2.5rem;
            color: #222;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        /* Block: grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 28px;
        }

        /* Block: card */
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1.5px 6px rgba(0, 0, 0, 0.04);
            transition: transform 0.15s, box-shadow 0.15s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card__link {
            color: inherit;
            text-decoration: none;
            display: block;
            height: 100%;
            transition: background 0.15s;
        }

        .card__link:hover,
        .card__link:focus {
            background: #f0f6ff;
        }

        .card__content {
            padding: 28px 22px 22px 22px;
        }

        .card__title {
            font-size: 1.3rem;
            margin: 0 0 10px 0;
            color: #1a237e;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .card__description {
            color: #555;
            font-size: 1rem;
            margin: 0;
        }

        .card__url {
            display: inline-block;
            background: #e3e7fa;
            color: #3949ab;
            font-size: 0.95em;
            border-radius: 4px;
            padding: 1px 6px;
            margin-left: 4px;
        }
    </style>
</head>

<?php
// Détection du protocole et de l’hôte
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$port_PHP_SERVER = 5600;
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:' . $port_PHP_SERVER; // Port par défaut si non défini
$baseUrl = $scheme . $host;
?>

<body class="page">
    <main class="page__main">
        <h1 class="page__title">Projets PHP</h1>
        <div class="grid">
            <?php foreach ($projets as $projet): ?>
                <article class="card">
                    <a class="card__link" href="<?php echo $baseUrl . '/' . htmlspecialchars($projet); ?>">
                        <div class="card__content">
                            <h2 class="card__title"><?php echo htmlspecialchars($projet); ?></h2>
                            <p class="card__description">
                                Accéder au projet <span class="card__url">/<?php echo htmlspecialchars($projet); ?></span>
                            </p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</body>


</html>

<!-- 
<a class="card__link" href="http://localhost/<?php echo htmlspecialchars($projet); ?>">
$_SERVER['HTTP_HOST'] contient le nom d’hôte et le port utilisés dans la requête (ex : localhost:84).
$_SERVER['REQUEST_SCHEME'] contient le protocole (http ou https). 
-->