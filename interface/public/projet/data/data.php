<?php

// Données de la barre de navigation
$links = [
    'home' => 'Accueil',
    'products' => 'Produits',
    'about' => 'A propos',
    'contact' => 'Contact'
];

$test = 'test';
file_put_contents('chemin', 'data');

// Données des produits
$products = [
    1 => [
        'id' => 1,
        'name' => 'Smartphone Pro',
        'description' => 'Un excellent smartphone avec écran HD et appareil photo 48MP. Parfait pour vos photos et vidéos.',
        'price' => 399.99,
        'category' => 'electronique',
        'image' => '📱',
        'stock' => 12,
        'featured' => true
    ],
    2 => [
        'id' => 2,
        'name' => 'Laptop Gaming',
        'description' => 'Ordinateur portable puissant pour gaming et travail. Processeur rapide et carte graphique dédiée.',
        'price' => 899.99,
        'category' => 'electronique',
        'image' => '💻',
        'stock' => 5,
        'featured' => true
    ],
    3 => [
        'id' => 3,
        'name' => 'Casque Bluetooth',
        'description' => 'Casque sans fil de qualité avec réduction de bruit. Autonomie de 20h.',
        'price' => 79.99,
        'category' => 'audio',
        'image' => '🎧',
        'stock' => 25,
        'featured' => false
    ],
    4 => [
        'id' => 4,
        'name' => 'Montre Connectée',
        'description' => 'Montre intelligente avec GPS et suivi sportif. Étanche et stylée.',
        'price' => 199.99,
        'category' => 'accessoires',
        'image' => '⌚',
        'stock' => 8,
        'featured' => true
    ],
    5 => [
        'id' => 5,
        'name' => 'Enceinte Portable',
        'description' => 'Enceinte Bluetooth compacte avec excellent son. Parfaite pour vos sorties.',
        'price' => 49.99,
        'category' => 'audio',
        'image' => '🔊',
        'stock' => 15,
        'featured' => false
    ],
    6 => [
        'id' => 6,
        'name' => 'Tablette 10 pouces',
        'description' => 'Tablette légère pour lecture, films et navigation. Écran haute résolution.',
        'price' => 299.99,
        'category' => 'electronique',
        'image' => '📱',
        'stock' => 7,
        'featured' => false
    ]
];
