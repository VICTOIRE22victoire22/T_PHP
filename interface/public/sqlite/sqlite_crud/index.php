<?php
// Configuration de la base de données
$db_file = 'users.db';

try {
    // Connexion à SQLite avec PDO
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Création de la table si elle n'existe pas
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        pseudo TEXT NOT NULL,
        email TEXT NOT NULL
    )";
    $pdo->exec($sql);
    
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Variables pour les messages
$message = '';
$message_type = '';
$confirm_delete_user = null;

// Récupérer utilisateur à confirmer pour suppression
if (isset($_GET['confirm_delete'])) {
    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_GET['confirm_delete']);
        $stmt->execute();
        $confirm_delete_user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$confirm_delete_user) {
            $message = "Utilisateur non trouvé !";
            $message_type = 'error';
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
        $message_type = 'error';
    }
}

// Traitement des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // AJOUTER un utilisateur
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        try {
            $sql = "INSERT INTO users (pseudo, email) VALUES (:pseudo, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':pseudo', $_POST['pseudo']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->execute();
            $message = "Utilisateur ajouté avec succès !";
            $message_type = 'success';
        } catch(PDOException $e) {
            $message = "Erreur lors de l'ajout : " . $e->getMessage();
            $message_type = 'error';
        }
    }
    
    // MODIFIER un utilisateur
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        try {
            $sql = "UPDATE users SET pseudo = :pseudo, email = :email WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':pseudo', $_POST['pseudo']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
            $message = "Utilisateur modifié avec succès !";
            $message_type = 'success';
        } catch(PDOException $e) {
            $message = "Erreur lors de la modification : " . $e->getMessage();
            $message_type = 'error';
        }
    }
    
    // SUPPRIMER un utilisateur
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
            $message = "Utilisateur supprimé avec succès !";
            $message_type = 'success';
        } catch(PDOException $e) {
            $message = "Erreur lors de la suppression : " . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// Récupérer tous les utilisateurs
try {
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $users = [];
    $message = "Erreur lors de la récupération des données : " . $e->getMessage();
    $message_type = 'error';
}

// Récupérer un utilisateur pour l'édition
$edit_user = null;
if (isset($_GET['edit'])) {
    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_GET['edit']);
        $stmt->execute();
        $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$edit_user) {
            $message = "Utilisateur non trouvé !";
            $message_type = 'error';
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP Natif - Gestion Utilisateurs</title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 1rem;
            line-height: 1.5;
        }
        
        /* Container principal */
        .container {
            max-width: 50rem;
            margin: 0 auto;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        /* Header */
        .header {
            background: #333;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .header__title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        /* Messages */
        .message {
            padding: 1rem 1.5rem;
            margin: 0;
            font-weight: bold;
        }
        
        .message--success {
            background: #d4edda;
            color: #155724;
            border-bottom: 0.0625rem solid #c3e6cb;
        }
        
        .message--error {
            background: #f8d7da;
            color: #721c24;
            border-bottom: 0.0625rem solid #f5c6cb;
        }
        
        /* Formulaire */
        .form {
            padding: 1.5rem;
            border-bottom: 0.0625rem solid #eee;
        }
        
        .form__title {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.125rem;
        }
        
        .form__group {
            margin-bottom: 1rem;
        }
        
        .form__label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: bold;
            color: #555;
        }
        
        .form__input {
            width: 100%;
            padding: 0.75rem;
            border: 0.0625rem solid #ddd;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        
        .form__input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.125rem rgba(0,123,255,0.25);
        }
        
        .form__actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        /* Boutons */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.2s;
            font-family: inherit;
        }
        
        .btn--primary {
            background: #007bff;
            color: white;
        }
        
        .btn--primary:hover {
            background: #0056b3;
        }
        
        .btn--success {
            background: #28a745;
            color: white;
        }
        
        .btn--success:hover {
            background: #1e7e34;
        }
        
        .btn--danger {
            background: #dc3545;
            color: white;
        }
        
        .btn--danger:hover {
            background: #c82333;
        }
        
        .btn--secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn--secondary:hover {
            background: #545b62;
        }
        
        .btn--small {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        /* Tableau */
        .table-container {
            padding: 1.5rem;
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .table__header {
            background: #f8f9fa;
        }
        
        .table__cell {
            padding: 0.75rem;
            border-bottom: 0.0625rem solid #dee2e6;
            text-align: left;
        }
        
        .table__cell--header {
            font-weight: bold;
            color: #495057;
        }
        
        .table__actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        /* Message vide */
        .empty {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            font-style: italic;
        }
        
        /* Responsive */
        @media (max-width: 30em) {
            body {
                padding: 0.5rem;
            }
            
            .container {
                border-radius: 0;
            }
            
            .header {
                padding: 1rem;
            }
            
            .header__title {
                font-size: 1.25rem;
            }
            
            .form,
            .table-container {
                padding: 1rem;
            }
            
            .form__actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .table__actions {
                flex-direction: column;
            }
            
            .btn--small {
                width: 100%;
                padding: 0.5rem;
            }
        }
        
        @media (max-width: 48em) {
            .table {
                font-size: 0.875rem;
            }
            
            .table__cell {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="header__title">CRUD PHP Natif - Gestion Utilisateurs</h1>
        </div>
        
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="message message--<?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <!-- Confirmation de suppression -->
        <?php if ($confirm_delete_user): ?>
            <div class="form" style="background: #fff3cd; border-bottom: 0.0625rem solid #ffeaa7;">
                <h2 class="form__title" style="color: #856404;">
                    ⚠️ Confirmer la suppression
                </h2>
                
                <p style="margin-bottom: 1rem; color: #856404;">
                    Êtes-vous sûr de vouloir supprimer l'utilisateur suivant ?<br>
                    <strong>Pseudo :</strong> <?= htmlspecialchars($confirm_delete_user['pseudo']) ?><br>
                    <strong>Email :</strong> <?= htmlspecialchars($confirm_delete_user['email']) ?>
                </p>
                
                <div class="form__actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $confirm_delete_user['id'] ?>">
                        <button type="submit" class="btn btn--danger">
                            ✓ Oui, supprimer définitivement
                        </button>
                    </form>
                    
                    <a href="index.php" class="btn btn--secondary">
                        ✗ Non, annuler
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire d'ajout/modification -->
        <?php if (!$confirm_delete_user): ?>
        <div class="form">
            <h2 class="form__title">
                <?= $edit_user ? 'Modifier l\'utilisateur #' . $edit_user['id'] : 'Ajouter un nouvel utilisateur' ?>
            </h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit_user ? 'edit' : 'add' ?>">
                <?php if ($edit_user): ?>
                    <input type="hidden" name="id" value="<?= $edit_user['id'] ?>">
                <?php endif; ?>
                
                <div class="form__group">
                    <label class="form__label" for="pseudo">Pseudo :</label>
                    <input 
                        type="text" 
                        id="pseudo" 
                        name="pseudo" 
                        class="form__input"
                        value="<?= $edit_user ? htmlspecialchars($edit_user['pseudo']) : '' ?>"
                        required
                        placeholder="Saisissez le pseudo"
                    >
                </div>
                
                <div class="form__group">
                    <label class="form__label" for="email">Email :</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form__input"
                        value="<?= $edit_user ? htmlspecialchars($edit_user['email']) : '' ?>"
                        required
                        placeholder="Saisissez l'email"
                    >
                </div>
                
                <div class="form__actions">
                    <button type="submit" class="btn btn--primary">
                        <?= $edit_user ? '✓ Sauvegarder les modifications' : '+ Ajouter l\'utilisateur' ?>
                    </button>
                    
                    <?php if ($edit_user): ?>
                        <a href="index.php" class="btn btn--secondary">✗ Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- Tableau des utilisateurs -->
        <div class="table-container">
            <h2>Liste des utilisateurs (<?= count($users) ?>)</h2>
            
            <?php if (empty($users)): ?>
                <div class="empty">
                    📝 Aucun utilisateur enregistré.<br>
                    Ajoutez-en un avec le formulaire ci-dessus !
                </div>
            <?php else: ?>
                <table class="table">
                    <thead class="table__header">
                        <tr>
                            <th class="table__cell table__cell--header">ID</th>
                            <th class="table__cell table__cell--header">Pseudo</th>
                            <th class="table__cell table__cell--header">Email</th>
                            <th class="table__cell table__cell--header">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="table__cell"><?= $user['id'] ?></td>
                                <td class="table__cell"><?= htmlspecialchars($user['pseudo']) ?></td>
                                <td class="table__cell"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="table__cell">
                                    <div class="table__actions">
                                        <a 
                                            href="?edit=<?= $user['id'] ?>" 
                                            class="btn btn--success btn--small"
                                            title="Modifier cet utilisateur"
                                        >
                                            ✏️ Éditer
                                        </a>
                                        
                                        <a 
                                            href="?confirm_delete=<?= $user['id'] ?>" 
                                            class="btn btn--danger btn--small"
                                            title="Supprimer cet utilisateur"
                                        >
                                            🗑️ Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>