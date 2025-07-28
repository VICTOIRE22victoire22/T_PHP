<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se souvenir du mot de passe</title>
    <style>
/* Variables CSS */
:root {
    --color-primary: #4f46e5;
    --color-primary-light: #6366f1;
    --color-success: #10b981;
    --color-bg: #f9fafb;
    --color-white: #fff;
    --color-border: #e5e7eb;
    --color-text: #1f2937;
    --color-text-light: #6b7280;
    --radius: 0.5rem;
    --spacing: 1.6rem;
    --font-size: 1.6rem;
    --shadow: 0 2px 8px 0 rgba(79,70,229,0.07);
}

/* Reset et base */
html { font-size: 62.5%; }
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--color-bg);
    color: var(--color-text);
    font-size: var(--font-size);
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing);
}

/* Remember container */
.remember {
    background: var(--color-white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: var(--spacing);
    width: 100%;
    max-width: 400px;
}

/* Titre */
.remember__title {
    font-size: 2.2rem;
    color: var(--color-primary);
    margin-bottom: 1.2rem;
    text-align: center;
}

/* Formulaire */
.remember__form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

/* Groupes de champs */
.remember__group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

/* Labels */
.remember__label {
    font-weight: 500;
    color: var(--color-text-light);
}

/* Inputs */
.remember__input {
    padding: 0.8rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-size: 1.6rem;
    transition: border-color 0.2s;
}

.remember__input:focus {
    border-color: var(--color-primary);
    outline: none;
}

/* Checkbox */
.remember__checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-top: 0.4rem;
}

.remember__checkbox {
    accent-color: var(--color-primary);
    width: 1.6rem;
    height: 1.6rem;
}

/* Boutons */
.remember__btn {
    background: var(--color-primary);
    color: var(--color-white);
    border: none;
    border-radius: var(--radius);
    padding: 1rem;
    font-size: 1.6rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 0.8rem;
    transition: background 0.2s, transform 0.2s;
    text-decoration: none;
    text-align: center;
    display: block;
}

.remember__btn:hover,
.remember__btn:focus {
    background: var(--color-primary-light);
    transform: translateY(-2px);
}

.remember__btn--secondary {
    background: var(--color-success);
    margin-top: 1rem;
}

.remember__btn--secondary:hover {
    background: #059669;
}

/* Messages */
.remember__info {
    margin-top: 1.2rem;
    text-align: center;
    color: var(--color-primary);
    font-size: 1.5rem;
}

.remember__logout-message {
    color: var(--color-success);
    margin-bottom: 1rem;
    text-align: center;
    font-weight: 500;
}

/* Media query pour les écrans plus grands */
@media (min-width: 40em) {
    body {
        padding: 3rem;
    }
    .remember {
        padding: 2.5rem;
    }
    .remember__title {
        font-size: 2.8rem;
    }
    .remember__form {
        gap: 2rem;
    }
    .remember__group {
        gap: 0.8rem;
    }
    .remember__label {
        font-size: 1.8rem;
    }
    .remember__input {
        padding: 1rem 1.2rem;
        font-size: 1.8rem;
    }
    .remember__btn {
        padding: 1.2rem;
        font-size: 1.8rem;
    }
    .remember__info {
        font-size: 1.7rem;
    }
}        
    </style>
</head>
<body>
<div class="remember">
    <h1 class="remember__title">🍪 Se souvenir du mot de passe</h1>
    
    <?php 

    // Le cookie $_COOKIE['remember'] existe-t-il / est-il défini ?
    if(isset($_COOKIE['remember'])) {
        $password = $_COOKIE['remember'];
        $remember = true;
    } else {
        $password = '';
        $remember = false;
    }

    // Récupération du mot de passe
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        // $remember = isset($_POST['remember']) ? '✅':'❌';
        $remember = isset($_POST['remember']);
        echo "Mot de passe : ". $password. "<br>";
        echo "Remember : ". $remember. "<br>";

        // Création du cookie

        // Si l'utilisateur a coché la checkbox
        if($remember) {
            $name = 'remember';
            $value = $password;   
            $expire = time() + 60; // 1 minute         
            $path = '/'; // accessible partout sur notre domaine
            $domain = '';
            $secure =false;
            $httponly = true;
            setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        } 
        // L'urilisateur n'a pas coché la checkbocx ou ne souhaite plus que son cookie demeure dans le navigateur : on le détruit !
        else {
            setcookie($name, '',time() - 3600);
        }
    }

    ?>
    
    <form method="post" class="remember__form">
        <div class="remember__group">
           <label for="password" class="remember__label">Mot de passe :</label>
            <input type="password" id="password" name="password" class="remember__input" value="<?= $password; ?>" placeholder="Tapez votre mot de passe">
        </div>
        <div class="remember__checkbox-group">
            <input type="checkbox" id="remember" name="remember" class="remember__checkbox" <?= $remember ? 'checked' : ''; ?>>
            <label for="remember" class="remember__label">Se souvenir de moi</label>
        </div>
        <button type="submit" class="remember__btn">Envoyer</button>
    </form>
    
    <?php if ($remember): ?> 
    <div>
        <a href="protected.php" class="remember__btn remember__btn--secondary">
            🔒 Accéder à la page protégée
        </a>
    </div>
    <?php endif ?> 

    <div class="remember__info">
        
    <?php if ($remember): ?> 
        <p>✅ Mot de passe enregistré dans un cookie.</p>
    <?php endif ?>
        
    <?php if (!$remember): ?> 
        <p>
            ❌ Le mot de passe ne sera pas enregistré.
        </p>
    <?php endif ?>

    </div>
</div>