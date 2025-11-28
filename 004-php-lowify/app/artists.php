<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once 'inc/utils.php';

$utils = (new Utils());
$database = $utils->connectDatabase();

//On tente de récupérer tous les artistes, sinon on renvoie sur la page d'erreur.
try {
    $artists = $database->executeQuery("SELECT * FROM artist");
}
catch(PDOException $e) {
    header("Location: error.php?message=Impossible de trouver les artistes dans la base de données.");
    exit();
}

$htmlArtists = "<div class='row'>";

//On attache toutes les informations nécessaires dans un seul string
foreach ($artists as $artist) {
    $htmlArtists =  $htmlArtists . "<a href='artist.php?artist=". $artist["id"] ."' class='imageblock'>";
    $htmlArtists = $htmlArtists . $artist["name"];
    $htmlArtists = $htmlArtists . "<img src=\"" . $artist["cover"] . "\">";
    $htmlArtists =  $htmlArtists . "</a>";
}

$htmlArtists =  $htmlArtists . "</div>";

//On génère la page HTML
$html = <<<HTML
    <h1>Artistes<br></h1>
    $htmlArtists
HTML;

//On envoie la page au client
echo (new HTMLPage("Lowify - Artistes"))
    ->addContent($html)
    ->addStylesheet("style.css")
    ->render();