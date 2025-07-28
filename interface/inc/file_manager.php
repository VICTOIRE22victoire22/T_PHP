<?php
// inc/file_manager.php - Fonctions de gestion des fichiers

/**
 * Scanne récursivement la structure des dossiers
 */
function scanDirectoryStructure($dir, $depth = 0)
{
    $structure = [];

    if (!is_dir($dir) || $depth > MAX_DEPTH) {
        return $structure;
    }

    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace(PUBLIC_DIR . DIRECTORY_SEPARATOR, '', $fullPath);

        if (is_dir($fullPath)) {
            $children = scanDirectoryStructure($fullPath, $depth + 1);
            $subFolders = array_filter($children, function ($child) {
                return $child['type'] === 'folder';
            });
            $files = array_filter($children, function ($child) {
                return $child['type'] === 'file';
            });

            $structure[$item] = [
                'type' => 'folder',
                'name' => $item,
                'path' => $relativePath,
                'children' => $children,
                'sub_folder_count' => count($subFolders),
                'file_count' => count($files),
                'total_files' => countFilesInDirectory($fullPath)
            ];
        } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
            $structure[$item] = [
                'type' => 'file',
                'name' => $item,
                'path' => $relativePath,
                'size' => filesize($fullPath),
                'modified' => filemtime($fullPath)
            ];
        }
    }

    // Tri : dossiers d'abord, puis fichiers
    uksort($structure, function ($a, $b) use ($structure) {
        $aType = $structure[$a]['type'];
        $bType = $structure[$b]['type'];

        if ($aType !== $bType) {
            return $aType === 'folder' ? -1 : 1;
        }

        return strnatcmp($a, $b);
    });

    return $structure;
}

/**
 * Compte le nombre de fichiers PHP dans un dossier
 */
function countFilesInDirectory($dir)
{
    $count = 0;

    if (!is_dir($dir)) {
        return $count;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $count++;
        }
    }

    return $count;
}

/**
 * Génère l'arbre de fichiers HTML
 */
function generateFileTree($structure, $currentPath = '', $currentFile = '')
{
    if (empty($structure)) {
        return '<div class="empty-folder">📂 Aucun dossier trouvé dans <code>public/</code></div>';
    }

    $html = '<ul class="file-tree">';

    foreach ($structure as $item) {
        if ($item['type'] === 'folder') {
            $isActive = strpos($currentPath, $item['path']) === 0;

            // Logique intelligente pour l'affichage du compteur
            $displayCount = '';
            $displayLabel = '';

            if ($item['sub_folder_count'] > 0) {
                // Si le dossier contient des sous-dossiers, afficher le nombre de sous-dossiers
                $displayCount = $item['sub_folder_count'];
                $displayLabel = $item['sub_folder_count'] > 1 ? 'dossiers' : 'dossier';
            } elseif ($item['file_count'] > 0) {
                // Sinon, afficher le nombre de fichiers
                $displayCount = $item['file_count'];
                $displayLabel = $item['file_count'] > 1 ? 'fichiers' : 'fichier';
            } else {
                // Dossier vide
                $displayCount = 'vide';
                $displayLabel = '';
            }

            $html .= '<li class="tree-item">';
            $html .= '<div class="tree-folder">';
            $html .= '<div class="folder-header ' . ($isActive ? 'active' : '') . '">';
            $html .= '<span>📁</span>';
            $html .= '<span>' . htmlspecialchars($item['name']) . '</span>';
            $html .= '<span style="margin-left: auto; font-size: 0.8em; color: rgba(255,255,255,0.8);" title="' . $displayLabel . '">(' . $displayCount . ')</span>';
            $html .= '</div>';

            $html .= '<div class="folder-content ' . ($isActive ? 'open' : '') . '">';

            // Lien vers le dossier
            $html .= '<a href="?path=' . urlencode($item['path']) . '" class="file-link">';
            $html .= '<span class="file-icon">📂</span>Voir le dossier';
            $html .= '</a>';

            // Fichiers du dossier
            foreach ($item['children'] as $child) {
                if ($child['type'] === 'file') {
                    $isFileActive = $currentPath === dirname($child['path']) && $currentFile === $child['name'];
                    $html .= '<a href="?path=' . urlencode(dirname($child['path'])) . '&file=' . urlencode($child['name']) . '" ';
                    $html .= 'class="file-link ' . ($isFileActive ? 'active' : '') . '">';
                    $html .= '<span class="file-icon">📄</span>';
                    $html .= htmlspecialchars(pathinfo($child['name'], PATHINFO_FILENAME));
                    $html .= '</a>';
                }
            }

            // Sous-dossiers
            $subFolders = array_filter($item['children'], function ($child) {
                return $child['type'] === 'folder';
            });

            if (!empty($subFolders)) {
                $html .= generateFileTree($subFolders, $currentPath, $currentFile);
            }

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</li>';
        }
    }

    $html .= '</ul>';
    return $html;
}

/**
 * Affiche la page d'accueil
 */
function displayHomepage($structure)
{
    echo '<h1>🏠 Bienvenue dans votre espace d\'apprentissage</h1>';

    echo '<div class="welcome-message">';
    echo '<h3>🎯 Organisez vos apprentissages</h3>';
    echo '<p>Cette interface vous permet de naviguer facilement dans vos dossiers et fichiers PHP. ';
    echo 'Créez votre structure personnalisée et voyez vos résultats en temps réel !</p>';
    echo '</div>';

    if (empty($structure)) {
        echo '<div class="instructions">';
        echo '<h3>🚀 Pour commencer :</h3>';
        echo '<ol>';
        echo '<li>Créez un dossier dans <code>public/</code> (ex: <code>exercices</code>, <code>cours</code>)</li>';
        echo '<li>Ajoutez des fichiers PHP dans vos dossiers</li>';
        echo '<li>Naviguez via le menu de gauche</li>';
        echo '<li>Cliquez sur un fichier pour voir son résultat</li>';
        echo '</ol>';
        echo '</div>';
        return;
    }

    echo '<h2>📁 Vos dossiers</h2>';
    echo '<div class="folder-grid">';

    foreach ($structure as $item) {
        if ($item['type'] === 'folder') {
            // Logique intelligente pour l'affichage
            $displayInfo = '';

            if ($item['sub_folder_count'] > 0 && $item['file_count'] > 0) {
                // Dossier mixte : sous-dossiers + fichiers
                $displayInfo = $item['sub_folder_count'] . ' dossier' . ($item['sub_folder_count'] > 1 ? 's' : '') .
                    ' • ' . $item['file_count'] . ' fichier' . ($item['file_count'] > 1 ? 's' : '');
            } elseif ($item['sub_folder_count'] > 0) {
                // Uniquement des sous-dossiers
                $displayInfo = $item['sub_folder_count'] . ' sous-dossier' . ($item['sub_folder_count'] > 1 ? 's' : '');
            } elseif ($item['file_count'] > 0) {
                // Uniquement des fichiers
                $displayInfo = $item['file_count'] . ' fichier' . ($item['file_count'] > 1 ? 's' : '');
            } else {
                // Dossier vide
                $displayInfo = 'Dossier vide';
            }

            echo '<a href="?path=' . urlencode($item['path']) . '" class="folder-card">';
            echo '<div class="folder-card-icon">📁</div>';
            echo '<div class="folder-card-title">' . htmlspecialchars($item['name']) . '</div>';
            echo '<div class="folder-card-info">' . $displayInfo . '</div>';
            echo '</a>';
        }
    }

    echo '</div>';
}

/**
 * Affiche le contenu d'un dossier
 */
function displayFolderContent($path)
{
    $fullPath = PUBLIC_DIR . DIRECTORY_SEPARATOR . $path;

    if (!is_dir($fullPath)) {
        echo '<h2>❌ Dossier non trouvé</h2>';
        echo '<p>Le dossier <code>' . htmlspecialchars($path) . '</code> n\'existe pas.</p>';
        return;
    }

    // Breadcrumb
    echo '<div class="breadcrumb">';
    echo '<a href="?">🏠 Accueil</a>';

    $pathParts = explode('/', $path);
    $currentPath = '';

    foreach ($pathParts as $part) {
        $currentPath .= ($currentPath ? '/' : '') . $part;
        echo ' → <a href="?path=' . urlencode($currentPath) . '">' . htmlspecialchars($part) . '</a>';
    }
    echo '</div>';

    echo '<h1>📁 ' . htmlspecialchars(basename($path)) . '</h1>';

    // Scan du dossier
    $structure = scanDirectoryStructure($fullPath, 0);

    if (empty($structure)) {
        echo '<div class="instructions">';
        echo '<h3>📝 Dossier vide</h3>';
        echo '<p>Ajoutez des fichiers PHP dans <code>' . htmlspecialchars($fullPath) . '</code></p>';
        echo '</div>';
        return;
    }

    // Affichage des fichiers
    $files = array_filter($structure, function ($item) {
        return $item['type'] === 'file';
    });

    if (!empty($files)) {
        echo '<h3>📄 Fichiers PHP</h3>';
        echo '<div class="folder-grid">';

        foreach ($files as $file) {
            echo '<a href="?path=' . urlencode($path) . '&file=' . urlencode($file['name']) . '" class="folder-card">';
            echo '<div class="folder-card-icon">📄</div>';
            echo '<div class="folder-card-title">' . htmlspecialchars(pathinfo($file['name'], PATHINFO_FILENAME)) . '</div>';
            echo '<div class="folder-card-info">' . date('H:i', $file['modified']) . ' • ' . formatBytes($file['size']) . '</div>';
            echo '</a>';
        }

        echo '</div>';
    }

    // Affichage des sous-dossiers
    $folders = array_filter($structure, function ($item) {
        return $item['type'] === 'folder';
    });

    if (!empty($folders)) {
        echo '<h3>📁 Sous-dossiers</h3>';
        echo '<div class="folder-grid">';

        foreach ($folders as $folder) {
            // Logique intelligente pour les sous-dossiers
            $displayInfo = '';

            if (isset($folder['sub_folder_count']) && isset($folder['file_count'])) {
                if ($folder['sub_folder_count'] > 0 && $folder['file_count'] > 0) {
                    $displayInfo = $folder['sub_folder_count'] . ' dossier' . ($folder['sub_folder_count'] > 1 ? 's' : '') .
                        ' • ' . $folder['file_count'] . ' fichier' . ($folder['file_count'] > 1 ? 's' : '');
                } elseif ($folder['sub_folder_count'] > 0) {
                    $displayInfo = $folder['sub_folder_count'] . ' sous-dossier' . ($folder['sub_folder_count'] > 1 ? 's' : '');
                } elseif ($folder['file_count'] > 0) {
                    $displayInfo = $folder['file_count'] . ' fichier' . ($folder['file_count'] > 1 ? 's' : '');
                } else {
                    $displayInfo = 'Vide';
                }
            } else {
                // Fallback pour la compatibilité
                $displayInfo = (isset($folder['file_count']) ? $folder['file_count'] : 0) . ' élément(s)';
            }

            echo '<a href="?path=' . urlencode($folder['path']) . '" class="folder-card">';
            echo '<div class="folder-card-icon">📁</div>';
            echo '<div class="folder-card-title">' . htmlspecialchars($folder['name']) . '</div>';
            echo '<div class="folder-card-info">' . $displayInfo . '</div>';
            echo '</a>';
        }

        echo '</div>';
    }
}

/**
 * Affiche le contenu d'un fichier avec gestion robuste des erreurs
 */
function displayFileContent($path, $filename)
{
    $fullPath = PUBLIC_DIR . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $filename;

    if (!file_exists($fullPath)) {
        echo '<h2>❌ Fichier non trouvé</h2>';
        return;
    }

    // Breadcrumb
    echo '<div class="breadcrumb">';
    echo '<a href="?">🏠 Accueil</a>';
    echo ' → <a href="?path=' . urlencode($path) . '">' . htmlspecialchars(basename($path)) . '</a>';
    echo ' → ' . htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME));
    echo '</div>';

    echo '<h1>📄 ' . htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME)) . '</h1>';

    echo '<div class="file-info">';
    echo '📁 Dossier: <code>' . htmlspecialchars($path) . '</code><br>';
    echo '📄 Fichier: <code>' . htmlspecialchars($filename) . '</code><br>';
    echo '📅 Modifié: ' . date('d/m/Y H:i:s', filemtime($fullPath)) . '<br>';
    echo '📏 Taille: ' . formatBytes(filesize($fullPath)) . '<br>';
    echo '<button onclick="location.reload()" style="background:#28a745;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;font-size:0.8em;margin-top:5px;">🔄 Actualiser</button>';
    echo '</div>';

    echo '<div class="file-viewer" id="fileOutput">';
    echo '<h3>🎯 Résultat du fichier : <small style="color: #28a745;">🔄 Auto-reload actif</small></h3>';

    // Exécution sécurisée avec validation syntaxique
    $result = executeFileSecurely($fullPath);

    if ($result['success']) {
        if (empty(trim($result['output']))) {
            echo '<div style="color: #666; font-style: italic;">';
            echo '💭 Aucun résultat affiché par ce fichier.';
            echo '</div>';
        } else {
            echo $result['output'];
        }
    } else {
        echo '<div style="color: red; background: #ffe6e6; padding: 15px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>❌ ' . htmlspecialchars($result['error_type']) . ' :</strong><br>';
        echo '<code>' . htmlspecialchars($result['error_message']) . '</code>';
        if ($result['line']) {
            echo '<br><small>📍 Ligne ' . $result['line'] . '</small>';
        }
        echo '</div>';

        // Afficher un conseil pour corriger l'erreur
        echo '<div style="background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>💡 Conseil :</strong> Corrigez l\'erreur dans votre éditeur et sauvegardez. ';
        echo 'La page se mettra à jour automatiquement !';
        echo '</div>';
    }

    echo '</div>';
}

/**
 * Exécute un fichier PHP de manière sécurisée SANS shell_exec
 * Version corrigée qui fonctionne sur tous les systèmes Laragon
 */
function executeFileSecurely($filePath)
{
    $result = [
        'success' => false,
        'output' => '',
        'error_type' => '',
        'error_message' => '',
        'line' => null
    ];

    // Vérification basique de l'existence du fichier
    if (!file_exists($filePath)) {
        $result['error_type'] = 'Erreur';
        $result['error_message'] = 'Fichier non trouvé';
        return $result;
    }

    // Vérification de la syntaxe PHP en utilisant token_get_all()
    $fileContent = file_get_contents($filePath);
    
    // Supprimer les erreurs de niveau NOTICE pendant la vérification
    $oldLevel = error_reporting(E_ERROR | E_WARNING | E_PARSE);
    
    // Test de syntaxe avec token_get_all
    $syntaxValid = true;
    $syntaxError = '';
    
    try {
        // token_get_all() lèvera une ParseError si la syntaxe est incorrecte
        token_get_all($fileContent);
    } catch (ParseError $e) {
        $syntaxValid = false;
        $syntaxError = $e->getMessage();
        $result['error_type'] = 'Erreur de syntaxe';
        $result['error_message'] = $syntaxError;
        $result['line'] = $e->getLine();
        error_reporting($oldLevel);
        return $result;
    }
    
    error_reporting($oldLevel);

    // Si la syntaxe est valide, exécuter le fichier
    ob_start();

    try {
        // Gestionnaire d'erreurs personnalisé
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        include $filePath;

        restore_error_handler();

        $result['success'] = true;
        $result['output'] = ob_get_clean();

    } catch (ParseError $e) {
        ob_end_clean();
        $result['error_type'] = 'Erreur de syntaxe';
        $result['error_message'] = $e->getMessage();
        $result['line'] = $e->getLine();

    } catch (Error $e) {
        ob_end_clean();
        $result['error_type'] = 'Erreur fatale';
        $result['error_message'] = $e->getMessage();
        $result['line'] = $e->getLine();

    } catch (Exception $e) {
        ob_end_clean();
        $result['error_type'] = 'Exception';
        $result['error_message'] = $e->getMessage();
        $result['line'] = $e->getLine();

    } catch (ErrorException $e) {
        ob_end_clean();
        $result['error_type'] = 'Avertissement';
        $result['error_message'] = $e->getMessage();
        $result['line'] = $e->getLine();
        $result['success'] = true; // Les avertissements n'empêchent pas l'exécution
        $result['output'] = ob_get_clean();
    }

    return $result;
}

/**
 * Génère un hash de la structure pour détecter les changements
 */
function generateStructureHash($structure)
{
    return md5(serialize($structure));
}

/**
 * Obtient le timestamp le plus récent d'un dossier (récursif)
 */
function getDirectoryTimestamp($dir)
{
    $latestTime = 0;

    if (!is_dir($dir)) {
        return $latestTime;
    }

    // Timestamp du dossier lui-même
    $latestTime = max($latestTime, filemtime($dir));

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        $latestTime = max($latestTime, $file->getMTime());
    }

    return $latestTime;
}

/**
 * Formate la taille des fichiers
 */
function formatBytes($size, $precision = 2)
{
    $units = ['o', 'Ko', 'Mo', 'Go'];
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
?>