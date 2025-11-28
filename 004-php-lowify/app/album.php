<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once "inc/utils.php";

$album = $_GET["album"] ?? "error404";

$utils = (new Utils());
$database = $utils->connectDatabase();

try {
    $albums = $database->executeQuery("
    SELECT album.name, album.id, album.cover, album.artist_id, album.release_date, artist.name artistname, song.name songname, song.duration, song.note FROM album
    INNER JOIN artist, song
    WHERE album.id = 2 AND album.artist_id = artist.id AND song.album_id = album.id
    ORDER BY song.id DESC
    ");
} catch(PDOException $e) {
    header("Location: error.php?message=Paramètre \"album\" manquant.");
    exit();
}

if(sizeof($albums) == 0) {
    header("Location: error.php?message=Album Introuvable");
    exit();
}

$album = $albums[0];
$albumName = $album["name"];
$albumCover = $album["cover"];
$artistName = $album["artistname"];
$artistId = $album["artist_id"];
$albumRelease = $album["release_date"];

$songs = "";

foreach ($albums as $album) {
    $duration = $utils->secondsToMin($album["duration"]);
    $songs = $songs . "<div>";
    $songs = $songs . $album["songname"] . " : " . $album["note"] . " (" . $duration[0] . ":" . $duration[1] . ")";
    $songs = $songs . "</div>";
}

$html = <<<HTML
    <h1>$albumName</h1>
     <div class="block">
        <a href="artist.php?artist=$artistId"><h2>$artistName</h2></a>
        <h2>Date de Sortie : $albumRelease</h2>
    </div>
    <div>
        <img src="$albumCover" alt="Cover de $albumName" class="cover">
    </div>
    <div class="block">
        <h2>Titres</h2>
        $songs
    </div>
HTML;

echo (new HTMLPage("Lowify - " . "Album Test"))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();