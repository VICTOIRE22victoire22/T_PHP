<?php

$charlie = "Charlie";

function bonjour()
{
    global $charlie;
    $alice = 'Alice';
    $bob = 'Bob';
    echo "Bonjour $charlie";
}

bonjour();