<?php

//echo __DIR__;

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
//echo "ROOT_PATH : " . ROOT_PATH . PHP_EOL;

define('INCLUDES_PATH', ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR);
//echo "INCLUDES_PATH : " . INCLUDES_PATH . PHP_EOL;

define('PAGES_PATH', ROOT_PATH . 'pages' . DIRECTORY_SEPARATOR);

define('PUBLIC_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);

define('DATA_PATH', ROOT_PATH . 'data' . DIRECTORY_SEPARATOR);

define('ASSETS_PATH', PUBLIC_PATH . 'assets' . DIRECTORY_SEPARATOR);

define('CSS_PATH', ASSETS_PATH . 'css' . DIRECTORY_SEPARATOR);

define('JS_PATH', ASSETS_PATH . 'js' . DIRECTORY_SEPARATOR);

define('IMG_PATH', ASSETS_PATH . 'img' . DIRECTORY_SEPARATOR);

// URL doivent être relatives coté client
define('CSS_URL', './public/assets/css/');
define('IMG_URL', './public/assets/img/');
define('JS_URL', './public//assets/js/');

// Nom du site
define('SITE_NAME', 'Mon_Site_Web');
define('SITE_AUTHOR', 'Moi');