<?php
// index.php - Interface de gestion de fichiers pour apprenants
session_start();

// Configuration
define('SITE_NAME', 'INTERFACE_PHP');
define('PUBLIC_DIR', 'public');
define('MAX_DEPTH', 3); // Profondeur maximale de dossiers

// Inclusion des fonctions
include_once 'inc/file_manager.php';

// API pour les opérations AJAX
if (isset($_GET['api'])) {
    header('Content-Type: application/json');

    switch ($_GET['api']) {
        case 'check_file':
            $filePath = $_GET['file'] ?? '';
            $lastKnown = intval($_GET['last'] ?? 0);

            $fullPath = PUBLIC_DIR . '/' . $filePath;
            $response = [
                'changed' => false,
                'timestamp' => 0,
                'exists' => file_exists($fullPath)
            ];

            if (file_exists($fullPath)) {
                $currentTimestamp = filemtime($fullPath);
                $response['timestamp'] = $currentTimestamp;
                $response['changed'] = $currentTimestamp > $lastKnown;
            }

            echo json_encode($response);
            exit;

        case 'check_structure':
            $lastKnown = intval($_GET['last'] ?? 0);
            $currentStructure = scanDirectoryStructure(PUBLIC_DIR);
            $structureHash = generateStructureHash($currentStructure);
            $currentTimestamp = getDirectoryTimestamp(PUBLIC_DIR);

            $response = [
                'changed' => false,
                'timestamp' => $currentTimestamp,
                'structure_hash' => $structureHash,
                'structure_changed' => false
            ];

            // Vérifier si la structure a changé (nouveaux dossiers/fichiers)
            $lastStructureHash = $_GET['hash'] ?? '';
            if ($lastStructureHash !== $structureHash) {
                $response['structure_changed'] = true;
                $response['changed'] = true;
            }

            // Vérifier si des fichiers ont été modifiés
            if ($currentTimestamp > $lastKnown) {
                $response['changed'] = true;
            }

            echo json_encode($response);
            exit;

        case 'get_structure':
            echo json_encode(scanDirectoryStructure(PUBLIC_DIR));
            exit;
    }
}

// Génération de la structure complète
$fileStructure = scanDirectoryStructure(PUBLIC_DIR);

// Routing basé sur les paramètres
$currentPath = $_GET['path'] ?? '';
$file = $_GET['file'] ?? '';

// Compteur de visites
if (!isset($_SESSION['visites'])) {
    $_SESSION['visites'] = 0;
}
$_SESSION['visites']++;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 90%;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            min-height: calc(100vh - 40px);
        }

        /* SIDEBAR */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            height: fit-content;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .sidebar-header h1 {
            color: #4a5568;
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .stats {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            padding: 8px;
            border-radius: 15px;
            font-size: 0.8em;
        }

        .file-tree {
            list-style: none;
        }

        .tree-item {
            margin: 2px 0;
        }

        .tree-folder {
            background: #f8f9fa;
            border-radius: 8px;
            margin: 5px 0;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .folder-header {
            padding: 10px 12px;
            background: #667eea;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .folder-header:hover {
            background: #764ba2;
        }

        .folder-header.active {
            background: #28a745;
        }

        .folder-content {
            padding: 8px;
            background: white;
            display: none;
        }

        .folder-content.open {
            display: block;
        }

        .file-link {
            display: block;
            padding: 6px 10px;
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            margin: 2px 0;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .file-link:hover {
            background: #e3f2fd;
            color: #1976d2;
            text-decoration: none;
        }

        .file-link.active {
            background: #e8f5e8;
            color: #155724;
            border-left: 3px solid #28a745;
        }

        .file-icon {
            margin-right: 6px;
            font-size: 0.9em;
        }

        .empty-folder {
            color: #6c757d;
            font-style: italic;
            font-size: 0.8em;
            padding: 5px 10px;
        }

        /* MAIN CONTENT */
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: calc(100vh - 60px);
        }

        .breadcrumb {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9em;
            border-left: 3px solid #667eea;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .file-viewer {
            background: #e8f5e8;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .file-viewer h3 {
            color: #155724;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .folder-overview {
            padding: 20px 0;
        }

        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .folder-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .folder-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .folder-card-icon {
            font-size: 2em;
            text-align: center;
            margin-bottom: 10px;
        }

        .folder-card-title {
            font-weight: bold;
            text-align: center;
            color: #667eea;
            margin-bottom: 5px;
        }

        .folder-card-info {
            font-size: 0.8em;
            color: #6c757d;
            text-align: center;
        }

        .auto-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(40, 167, 69, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            z-index: 1000;
            display: none;
        }

        .auto-indicator.active {
            display: block;
            animation: pulse 2s infinite;
        }

        .auto-indicator.structure {
            background: rgba(54, 162, 235, 0.9);
            display: block;
        }

        .auto-indicator.structure-changed {
            background: rgba(255, 193, 7, 0.9);
            display: block;
            animation: structureChange 1s ease-in-out infinite;
        }

        @keyframes structureChange {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .welcome-message {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }

        .instructions {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 3px solid #ffc107;
        }

        .file-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 0.9em;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .sidebar {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div id="autoIndicator" class="auto-indicator">
        🔄 Surveillance active
    </div>

    <div class="container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>📁 Mes Fichiers</h1>
                <div class="stats">
                    Visites: <?= $_SESSION['visites'] ?> | <?= date('H:i:s') ?>
                </div>
            </div>

            <div id="fileTree">
                <?= generateFileTree($fileStructure, $currentPath, $file) ?>
            </div>

            <div class="instructions">
                <strong>💡 Comment utiliser :</strong>
                <ol style="font-size: 0.8em; margin-top: 8px;">
                    <li>Créez des dossiers dans <code>public/</code></li>
                    <li>Ajoutez vos fichiers PHP</li>
                    <li>Naviguez via cette interface</li>
                    <li>Modifiez vos fichiers → Voir en temps réel !</li>
                </ol>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <?php
            if ($file) {
                // Affichage d'un fichier spécifique
                displayFileContent($currentPath, $file);
            } elseif ($currentPath) {
                // Affichage du contenu d'un dossier
                displayFolderContent($currentPath);
            } else {
                // Page d'accueil
                displayHomepage($fileStructure);
            }
            ?>
        </main>
    </div>

    <script>
        // Gestion de l'interface
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle des dossiers
            document.querySelectorAll('.folder-header').forEach(header => {
                header.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    const isOpen = content.classList.contains('open');

                    if (isOpen) {
                        content.classList.remove('open');
                        this.classList.remove('active');
                    } else {
                        content.classList.add('open');
                        this.classList.add('active');
                    }
                });
            });

            // Surveillance globale des changements de structure
            let structureHash = '<?= generateStructureHash($fileStructure) ?>';
            let lastStructureTimestamp = <?= getDirectoryTimestamp(PUBLIC_DIR) ?>;
            let isCheckingStructure = false;

            function checkStructureChanges() {
                if (isCheckingStructure) return;

                isCheckingStructure = true;
                const url = `?api=check_structure&last=${lastStructureTimestamp}&hash=${encodeURIComponent(structureHash)}&t=${Date.now()}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.structure_changed) {
                            // Nouveaux dossiers/fichiers détectés
                            const indicator = document.getElementById('autoIndicator');
                            indicator.textContent = '📁 Nouvelle structure détectée...';
                            indicator.style.background = 'rgba(255, 193, 7, 0.9)';
                            indicator.classList.add('active');

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }

                        lastStructureTimestamp = data.timestamp;
                        structureHash = data.structure_hash;
                        isCheckingStructure = false;
                    })
                    .catch(error => {
                        console.error('Erreur surveillance structure:', error);
                        isCheckingStructure = false;
                    });
            }

            // Surveillance de la structure toutes les 2 secondes
            setInterval(checkStructureChanges, 2000);

            // Auto-refresh pour les fichiers (robuste même avec erreurs)
            <?php if ($file): ?>
                const currentFile = '<?= addslashes($currentPath . '/' . $file) ?>';
                let lastTimestamp = <?= file_exists(PUBLIC_DIR . '/' . $currentPath . '/' . $file) ? filemtime(PUBLIC_DIR . '/' . $currentPath . '/' . $file) : 0 ?>;
                let isReloading = false;

                function checkFileChanges() {
                    if (isReloading) return;

                    const url = `?api=check_file&file=${encodeURIComponent(currentFile)}&last=${lastTimestamp}&t=${Date.now()}`;

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.changed && !isReloading) {
                                isReloading = true;
                                const indicator = document.getElementById('autoIndicator');
                                indicator.textContent = '🔄 Fichier modifié...';
                                indicator.style.background = 'rgba(220, 53, 69, 0.9)';

                                // Actualisation immédiate même si il y a des erreurs
                                setTimeout(() => {
                                    window.location.reload();
                                }, 300);
                            }
                            lastTimestamp = data.timestamp;
                        })
                        .catch(error => {
                            console.error('Erreur de vérification:', error);
                            // En cas d'erreur de connexion, on continue quand même à vérifier
                        });
                }

                // Activer l'indicateur et la surveillance
                const indicator = document.getElementById('autoIndicator');
                indicator.classList.add('active');
                indicator.textContent = '🔄 Surveillance fichier + structure';

                // Vérification plus fréquente pour une meilleure réactivité
                const checkInterval = setInterval(checkFileChanges, 800);

                // Nettoyage quand on quitte la page
                window.addEventListener('beforeunload', function () {
                    clearInterval(checkInterval);
                });

                console.log('🚀 Auto-reload actif pour:', currentFile);
            <?php else: ?>
                // Surveillance uniquement de la structure si pas de fichier ouvert
                const indicator = document.getElementById('autoIndicator');
                indicator.classList.add('active');
                indicator.textContent = '📁 Surveillance structure';
                indicator.style.background = 'rgba(54, 162, 235, 0.9)';

                console.log('📁 Surveillance de la structure active');
            <?php endif; ?>

            // Raccourci pour actualisation manuelle en cas de problème
            document.addEventListener('keydown', function (e) {
                if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                    e.preventDefault();
                    if (typeof isReloading !== 'undefined') isReloading = true;
                    location.reload();
                }
            });

            console.log('💡 F5 ou Ctrl+R pour actualisation manuelle');
        });
    </script>
</body>

</html>