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

//On attache toutes les informations nécessaires dans un seul string
$htmlArtists = "";
foreach ($artists as $artist) {
    $artistId = $artist["id"];
    $htmlArtists = $htmlArtists . "<a href='artist.php?artist=$artistId'><div class='block'>";
    $htmlArtists = $htmlArtists . "<h2>" . $artist["name"] . "</h2>";
    $htmlArtists = $htmlArtists . "<img src=\"" . $artist["cover"] . "\">";
    $htmlArtists =  $htmlArtists . "</div></a>";
}

//On génère la page HTML
$html = <<<HTML
    <a href="index.php"><h1>Artistes</h1></a>
    <div class="artists">
        $htmlArtists
    </div>
HTML;

//On envoie la page au client
echo (new HTMLPage("Lowify - Artistes"))
    ->addContent($html)
    ->addStylesheet("style.css")
    ->render();