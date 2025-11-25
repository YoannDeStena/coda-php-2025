<?php

$hasPlayer = isset($_GET['player']);
$games = $_GET["games"] ?? 0;
$playerWins = $_GET["playerwins"] ?? 0;
$phpWins = $_GET["phpwins"] ?? 0;
$ties = $_GET["ties"] ?? 0;
$player = $_GET["player"] ?? "Choississez un attaquant";
$phpPlayer = "En Attente";
$choices = ["Pierre", "Feuille", "Ciseaux", "Lézard", "Spock"];
$result = "Aucun";

function getPlayerStrengths(String $player): array {
    $toReturn = null;
    switch ($player) {
        case "Pierre": $toReturn = ["Ciseaux", "Lézard"]; break;
        case "Feuille": $toReturn = ["Pierre", "Spock"]; break;
        case "Ciseaux": $toReturn = ["Feuille", "Lézard"]; break;
        case "Lézard": $toReturn = ["Feuille", "Spock"]; break;
        case "Spock": $toReturn = ["Pierre", "Ciseaux"]; break;
    }
    return $toReturn;
}

if($hasPlayer) {
    $phpPlayer = $choices[array_rand($choices)];
    if($player == $phpPlayer) {
        $result = "Égalité";
        $ties += 1;
    } elseif(in_array($phpPlayer, getPlayerStrengths($player))) {
        $result = "Gagné";
        $playerWins += 1;
    } else {
        $result = "Perdu";
        $phpWins += 1;
    }
    $games += 1;
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
            <h2>Parties Jouées : $games</h2>
            <h2>Victoires (Joueur) : $playerWins</h2>
            <h2>Victoires (PHP) : $phpWins</h2>
            <h2>Égalités : $ties</h2>
        </div>
        <div class="row">
            <h2>Joueur : $player</h2>
            <h2>PHP : $phpPlayer</h2>
            <h2>Résultat : $result</h2>
        </div>
        <div class="row">
            <a href="?player=Pierre&games=$games&playerwins=$playerWins&phpwins=$phpWins&ties=$ties"><button type="submit" >Pierre</button></a>
            <a href="?player=Feuille&games=$games&playerwins=$playerWins&phpwins=$phpWins&ties=$ties"><button type="submit">Feuille</button></a>
            <a href="?player=Ciseaux&games=$games&playerwins=$playerWins&phpwins=$phpWins&ties=$ties"><button type="submit">Ciseaux</button></a>
            <a href="?player=Lézard&games=$games&playerwins=$playerWins&phpwins=$phpWins&ties=$ties"><button type="submit">Lézard</button></a>
            <a href="?player=Spock&games=$games&playerwins=$playerWins&phpwins=$phpWins&ties=$ties"><button type="submit">Spock</button></a>
        </div>
        <div class="row">
            <a href="index.php"><button type="submit">Reset</button></a>
        </div>
    </body>
    </html>
HTML;

echo $html;