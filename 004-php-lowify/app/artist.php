<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once 'inc/utils.php';

$artist = $_GET["artist"] ?? "error404";

$utils = (new Utils());
$database = $utils->connectDatabase();

//On essaie de récupérer toutes les informations nécessaires, on renvoie une erreur sinon.
try {
    $artists = $database->executeQuery("SELECT * FROM artist WHERE id = $artist");
    $songs = $database->executeQuery("
        SELECT song.name, song.id, song.note, song.duration, album.cover, album.id FROM song     
        INNER JOIN artist, album
        WHERE song.artist_id = artist.id AND song.artist_id = $artist AND song.album_id = album.id
        ORDER BY song.note DESC
        LIMIT 5");
    $albums = $database->executeQuery(" 
        SELECT * FROM album
        WHERE artist_id = $artist
        ORDER BY release_date DESC
    ");
} catch(PDOException $e) {
    header("Location: error.php?message=Paramètre \"artist\" manquant.");
    exit();
}

//On vérifie si l'artiste existe vraiment ou si l'ID est invalide.
if(sizeof($artists) == 0) {
    header("Location: error.php?message=Artiste Introuvable");
    exit();
}

$artist = $artists[0];
$artistName = $artist["name"];
$artistCover = $artist["cover"];
$artistBiography = $artist["biography"];

//On attache toutes les informations nécessaires dans un seul string
$songText = "";
foreach($songs as $song) {
    $duration = $utils->secondsToMin($song["duration"]);
    $minutes = $duration[0];
    $seconds = $duration[1];
    $songText = $songText . "<div class='block'>";
    $songText = $songText . "<h2>" . $song["name"] . "</h2>";
    $songText = $songText . "<p>Note : " . $song["note"] . " | Durée : $minutes:$seconds</p>";
    $songText = $songText . "<img src=\"" . $song["cover"] . "\"/>";
    $songText = $songText . "</div>";
}

//On attache toutes les informations nécessaires dans un seul string
$albumText = "";
foreach($albums as $album) {
    $albumId = $album["id"];
    $date = $utils->formatDate($album["release_date"]);
    $albumText = $albumText . "<a href='album.php?album=$albumId'><div class='block'>";
    $albumText = $albumText . "<h2>" . $album["name"] . "</h2>";
    $albumText = $albumText . "<p>Date de Sortie : $date</p>";
    $albumText = $albumText . "<img src=\"" . $album["cover"] . "\"/>";
    $albumText = $albumText . "</div></a>";
}

$viewers = $utils->formatViewers($artist["monthly_listeners"]);

//On génère la page HTML
$html = <<<HTML
    <a href="index.php"><h1>$artistName</h1></a>
    <div class="block">
        <h2>Auditeurs Mensuels</h2>
        <p>$viewers</p>
    </div>
    <div>
        <img src="$artistCover" alt="Cover de $artistName" class="cover">
    </div>
    <div class="block">
        <h2>Biographie</h2>
        <p>$artistBiography</p>
    </div>
    <h3>Top Titres</h3>
    <div class="songs">
        $songText
    </div>
    <h3>Albums</h3>
    <div class="songs">
        $albumText
    </div>
HTML;

//On envoie la page au client
echo (new HTMLPage("Lowify - " . $artistName))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();