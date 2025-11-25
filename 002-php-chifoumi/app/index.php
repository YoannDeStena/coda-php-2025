<?php

$hasPlayer = isset($_GET['player']);
$player = $_GET["player"] ?? "Choississez un attaquant";
$choices = ["Pierre", "Feuille", "Ciseaux"];
$phpPlayer = $choices[array_rand($choices)];
$result = "Aucun";

function getPlayerStrengths(String $player): array {
    $toReturn = null;
    switch ($player) {
        case "Pierre": $toReturn = ["Ciseaux"]; break;
        case "Feuille": $toReturn = ["Pierre"]; break;
        case "Ciseaux": $toReturn = ["Feuille"]; break;
    }
    return $toReturn;
}

if($hasPlayer) {
    if($player == $phpPlayer) {
        $result = "Égalité";
    } elseif(in_array($phpPlayer, getPlayerStrengths($player))) {
        $result = "Gagné";
    } else {
        $result = "Perdu";
    }
}


$html = <<<HTML
<!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pierre, Feuille, Ciseaux </title>
        <link type="text/css" rel="stylesheet" href="style.css">
        
    </head>
    <body>
        <h1>Jeu Pierre, Feuille, Ciseaux</h1>
        <div class="row">
            <h2>Joueur : $player</h2>
            <h2>PHP : $phpPlayer</h2>
            <h2>Resultat : $result</h2>
        </div>
        <div class="row">
            <a href="index.php?player=Pierre"><button type="submit" >Pierre</button></a>
            <a href="index.php?player=Feuille"><button type="submit">Feuille</button></a>
            <a href="index.php?player=Ciseaux"><button type="submit">Ciseaux</button></a>
        </div>
        <div class="row">
            <a href="index.php"><button type="submit">Reset</button></a>
        </div>
    </body>
    </html>
HTML;

echo $html;