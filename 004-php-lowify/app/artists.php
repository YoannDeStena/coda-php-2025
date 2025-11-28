<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once 'inc/utils.php';

$utils = (new Utils());
$database = $utils->connectDatabase();

$htmlArtists = "<div class='row'>";

foreach ($artists as $artist) {
    $htmlArtists =  $htmlArtists . "<a href='artist.php?artist=". $artist["id"] ."' class='imageblock'>";
    $htmlArtists = $htmlArtists . $artist["name"];
    $htmlArtists = $htmlArtists . "<img src=\"" . $artist["cover"] . "\">";
    $htmlArtists =  $htmlArtists . "</a>";
}

$htmlArtists =  $htmlArtists . "</div>";

$html = <<<HTML
    <h1>Artistes<br></h1>
    $htmlArtists
HTML;

echo (new HTMLPage("Lowify - Artistes"))
    ->addContent($html)
    ->addStylesheet("style.css")
    ->render();