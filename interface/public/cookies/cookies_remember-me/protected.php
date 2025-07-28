<?php 
if(isset($_COOKIE['remember'])) {
        $password = $_COOKIE['remember'];
        $remember = true;
    } else {
        $password = '';
        $remember = false;
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page protégée</title>
    <style>
        /* Même CSS que index.php */
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

        .protected {
            background: var(--color-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .protected__title {
            font-size: 2.4rem;
            color: var(--color-success);
            margin-bottom: 1.2rem;
        }

        .protected__message {
            font-size: 1.6rem;
            color: var(--color-text-light);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .protected__cookie-info {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 2rem;
            font-size: 1.4rem;
        }

        .protected__actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .protected__btn {
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            border-radius: var(--radius);
            padding: 1rem 2rem;
            font-size: 1.6rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }

        .protected__btn--danger {
            background: #ef4444;
        }

        .protected__btn:hover,
        .protected__btn:focus {
            background: var(--color-primary-light);
            transform: translateY(-2px);
        }

        .protected__btn--danger:hover {
            background: #dc2626;
        }

        @media (min-width: 40em) {
            .protected {
                padding: 2.5rem;
            }
            .protected__title {
                font-size: 3rem;
            }
            .protected__message {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="protected">
        <h1 class="protected__title">🎉 Accès autorisé !</h1>
        <p class="protected__message">
            Félicitations ! Vous accédez à cette page protégée grâce au cookie "Se souvenir de moi".
        </p>
        <div class="protected__cookie-info">
            <strong>Cookie détecté :</strong> <?= $password ?>
        </div>
        <div class="protected__actions">
            <a href="index.php" class="protected__btn">Retour à l'accueil</a>
            <a href="logout.php" class="protected__btn protected__btn--danger">Se déconnecter</a>
        </div>
    </div>
</body>
</html>