<?php

require DATA_PATH . 'data.php';

function generate_navbar()
{
    global $links;

    foreach ($links as $pg => $page) {
        // page courante
        $page_courante = isset($_GET['pg']) ? $_GET['pg'] : 'home';

        // classe
        // Si $pg est la page courrante on met la classe "active" sinon on ne met rien
        $class = ($pg === $page_courante) ? 'active' : '';

        // <a href="index.php?pg=contact">Contact</a>
        echo "<a href='index.php?pg=$pg' class='$class'>$page</a>";
    }
}

function display_all_products()
{
    global $products;

    echo "<div class='cards'>";
    foreach ($products as $product):
        echo "<div class='card'>";
        foreach ($product as $key => $value): ?>
            <div> <?= $value; ?> </div>
        <?php endforeach;
        echo "<button class='card__button'><a href='index.php?pg=product&id=" . $product['id'] . "'>Voir</a></button>";
        echo "</div>";
    endforeach;
    echo "</div>";
}

function display_product($id)
{
    global $products;

    $indicateur = false;

    echo "<div class='product__card'>";
    foreach ($products as $product) {
        foreach ($product as $key => $value) {
            if ($product['id'] === $id) {
                echo "<div>$value</div>";
                $indicateur = true;
            }
        }
    }
    if ($indicateur === false) {
        echo "Produit inexistant !";
    }
    echo "</div>";
}
