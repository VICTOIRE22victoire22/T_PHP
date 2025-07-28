<?php
// inc/functions.php - Version avec auto-reload automatique

/**
 * Génère le menu à partir des dossiers
 */
function generateMenuFromFolders($baseDir)
{
    $menuItems = [];

    if (!is_dir($baseDir)) {
        return $menuItems;
    }

    $folders = scandir($baseDir);

    foreach ($folders as $folder) {
        if ($folder === '.' || $folder === '..') {
            continue;
        }

        $folderPath = $baseDir . DIRECTORY_SEPARATOR . $folder;

        if (is_dir($folderPath)) {
            $exerciseCount = countExercisesInFolder($folderPath);
            if ($exerciseCount > 0) {
                $menuItems[$folder] = $exerciseCount;
            }
        }
    }

    return $menuItems;
}

/**
 * Compte les exercices dans un dossier
 */
function countExercisesInFolder($folderPath)
{
    $count = 0;
    $files = scandir($folderPath);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $count++;
        }
    }

    return $count;
}

/**
 * Affiche la liste des exercices d'un module
 */
function displayExercisesList($folderName)
{
    $folderPath = EXERCISES_DIR . DIRECTORY_SEPARATOR . $folderName;

    if (!is_dir($folderPath)) {
        echo '<div class="empty-folder">';
        echo '<h2>📁 Module non trouvé</h2>';
        echo '<p>Le module "' . htmlspecialchars($folderName) . '" n\'existe pas.</p>';
        echo '</div>';
        return;
    }

    $exercises = getExercisesList($folderPath);

    echo '<h2>📁 Module : ' . ucfirst($folderName) . '</h2>';

    if (empty($exercises)) {
        echo '<div class="empty-folder">';
        echo '<h3>📝 Aucun exercice trouvé</h3>';
        echo '<p>Créez des fichiers PHP dans <code>exercices/' . htmlspecialchars($folderName) . '/</code></p>';
        echo '<div class="code-block">';
        echo 'mkdir exercices/' . htmlspecialchars($folderName) . "\n";
        echo 'echo "&lt;?php echo \'Hello World !\'; ?&gt;" > exercices/' . htmlspecialchars($folderName) . '/exercice1.php';
        echo '</div>';
        echo '</div>';
        return;
    }

    echo '<div class="highlight">';
    echo '<h3>🚀 Auto-reload activé !</h3>';
    echo '<p>Cliquez sur un exercice, puis modifiez le fichier PHP dans votre éditeur. Les changements apparaîtront automatiquement !</p>';
    echo '</div>';

    echo '<div class="exercise-grid">';

    foreach ($exercises as $index => $exercise) {
        echo '<a href="?page=' . urlencode($folderName) . '&exercise=' . urlencode($exercise['filename']) . '" class="exercise-card">';
        echo '<div class="exercise-number">' . ($index + 1) . '</div>';
        echo '<div class="exercise-title">' . $exercise['title'] . '</div>';
        echo '</a>';
    }

    echo '</div>';
}

/**
 * Affiche un exercice spécifique avec auto-reload automatique
 */
function displaySingleExercise($folderName, $exerciseFile)
{
    $exercisePath = EXERCISES_DIR . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $exerciseFile;

    if (!file_exists($exercisePath)) {
        echo '<h2>❌ Exercice non trouvé</h2>';
        echo '<p>Le fichier <code>' . htmlspecialchars($exercisePath) . '</code> n\'existe pas.</p>';
        return;
    }

    $lastModified = filemtime($exercisePath);
    $formattedTime = date('d/m/Y H:i:s', $lastModified);

    // Breadcrumb
    echo '<div class="breadcrumb">';
    echo '<a href="?page=' . urlencode($folderName) . '">📁 ' . ucfirst($folderName) . '</a>';
    echo ' → ' . ucfirst(pathinfo($exerciseFile, PATHINFO_FILENAME));
    echo '</div>';

    // Info fichier et bouton retour
    echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">';
    echo '<a href="?page=' . urlencode($folderName) . '" class="back-btn">← Retour à la liste</a>';
    echo '<div class="file-info">📄 ' . $exerciseFile . ' • Modifié le ' . $formattedTime . '</div>';
    echo '</div>';

    // Titre avec indicateur auto-reload
    $exerciseTitle = ucfirst(pathinfo($exerciseFile, PATHINFO_FILENAME));
    echo '<h2>📝 ' . $exerciseTitle . ' <small style="color: #28a745; font-size: 0.6em;">🔄 AUTO-RELOAD</small></h2>';

    // Message d'instructions
    echo '<div class="highlight">';
    echo '<h3>🎯 Instructions :</h3>';
    echo '<p>Modifiez le fichier <code>' . htmlspecialchars($exercisePath) . '</code> dans votre éditeur et sauvegardez. ';
    echo 'La page se mettra à jour automatiquement en 1 seconde !</p>';
    echo '</div>';

    // Résultat dans le cadre vert
    echo '<div class="result-section">';
    echo '<h3>🎯 Résultat de l\'exercice :</h3>';

    // Exécution de l'exercice avec gestion d'erreurs complète
    ob_start();
    try {
        // Capture des erreurs PHP
        $errorHandler = set_error_handler(function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        include $exercisePath;

        // Restaurer l'ancien gestionnaire d'erreurs
        restore_error_handler();

    } catch (ParseError $e) {
        echo '<div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>❌ Erreur de syntaxe :</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '<br><small>Ligne ' . $e->getLine() . '</small>';
        echo '</div>';
    } catch (Error $e) {
        echo '<div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>❌ Erreur fatale :</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '<br><small>Ligne ' . $e->getLine() . '</small>';
        echo '</div>';
    } catch (Exception $e) {
        echo '<div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>❌ Exception :</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '<br><small>Ligne ' . $e->getLine() . '</small>';
        echo '</div>';
    } catch (ErrorException $e) {
        echo '<div style="color: orange; background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">';
        echo '<strong>⚠️ Avertissement :</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '<br><small>Ligne ' . $e->getLine() . '</small>';
        echo '</div>';
    }

    $output = ob_get_clean();

    if (empty(trim($output))) {
        echo '<div style="color: #666; font-style: italic; padding: 10px; background: #f8f9fa; border-radius: 5px;">';
        echo '💭 Aucun résultat affiché par cet exercice.<br>';
        echo '<small>Ajoutez des <code>echo</code> ou <code>print</code> dans votre code pour voir du contenu.</small>';
        echo '</div>';
    } else {
        echo $output;
    }

    echo '</div>';

    // Exemple de code pour guider l'utilisateur
    echo '<div class="card">';
    echo '<h3>💡 Exemple de code :</h3>';
    echo '<div class="code-block">';
    echo htmlspecialchars('<?php
echo "<h4>Mon exercice</h4>";
echo "<p>Bonjour le monde !</p>";

$nombre = 5;
echo "<p>Le nombre est : $nombre</p>";
?>');
    echo '</div>';
    echo '<p><small>Copiez ce code dans votre fichier pour tester l\'auto-reload !</small></p>';
    echo '</div>';
}

/**
 * Récupère la liste des exercices
 */
function getExercisesList($folderPath)
{
    $exercises = [];
    $files = scandir($folderPath);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $exercises[] = [
                'filename' => $file,
                'title' => ucfirst(pathinfo($file, PATHINFO_FILENAME))
            ];
        }
    }

    // Tri naturel pour avoir exercice1, exercice2, exercice10...
    usort($exercises, function ($a, $b) {
        return strnatcmp($a['filename'], $b['filename']);
    });

    return $exercises;
}
?>