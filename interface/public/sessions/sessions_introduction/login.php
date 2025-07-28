<?php 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <p>
        
        <?php 
        // Si l'utilisateur n'est pasa connecté, il faut prévoir l'affichage d'une notification.
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }
        if (isset($_GET['msg']) && (int) $_GET['msg'] === 1) {
            echo "Déconnexion réussie !";
        }
        ?>
    </p>
    <h2>Connexion</h2>
    <form method="post" action="process_login.php">
        <label for="username">Nom d'utilisateur:</label><br>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <label for="check">Mot de passe:</label><br>
        <input type="checkbox" id="check" name="checkbox"><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>