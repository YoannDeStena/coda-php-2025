<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';

$artist = $_GET["artist"] ?? "error404";

try {

    $database = (new DatabaseManager(
        "mysql:host=mysql;dbname=lowify;charset=utf8mb4",
        "lowify",
        "lowifypassword"));
} catch (PDOException $e) {
    $message = $e->getMessage();
    echo (new HTMLPage("Lowify - Error"))
        ->addStylesheet("style.css")
        ->addContent("<h1>$message</h1>")
        ->render();
    return;
}

$artists = $database->executeQuery("SELECT * FROM artist WHERE id = $artist");
$songs = $database->executeQuery("
    SELECT song.name, song.id, song.note, album.cover FROM song     
    INNER JOIN artist, album
    WHERE song.artist_id = artist.id AND song.artist_id = $artist AND song.album_id = album.id
    ORDER BY song.note DESC
    LIMIT 5");
$albums = $database->executeQuery(" 
    SELECT * FROM album
    WHERE artist_id = $artist
    ORDER BY release_date DESC
");

if(sizeof($artists) == 0) {
    echo (new HTMLPage("Lowify - Error 404"))
        ->addStylesheet("style.css")
        ->addContent("<h1>Artiste introuvable.</h1>")
        ->render();
    return;
}

$artist = $artists[0];
$artistName = $artist["name"];
$artistCover = $artist["cover"];
$artistBiography = $artist["biography"];

$songText = "";

foreach($songs as $song) {
    $songText = $songText . "<div class='block'>";
    $songText = $songText . "<h2>" . $song["name"] . "</h2>";
    $songText = $songText . "<p>Note : " . $song["note"] . "</p>";
    $songText = $songText . "<img src=\"" . $song["cover"] . "\"/>";
    $songText = $songText . "</div>";
}

$albumText = "";

foreach($albums as $album) {
    $albumText = $albumText . "<div class='block'>";
    $albumText = $albumText . "<h2>" . $album["name"] . "</h2>";
    $albumText = $albumText . "<p>Date de Sortie : " . $album["release_date"] . "</p>";
    $albumText = $albumText . "<img src=\"" . $album["cover"] . "\"/>";
    $albumText = $albumText . "</div>";
}

$html = <<<HTML
    <h1>$artistName</h1>
    <div>
        <img src="$artistCover" alt="Cover de $artistName" class="cover">
    </div>
    <div class="block">
        <h2>Biographie</h2>
        <p>$artistBiography</p>
    </div>
    <h3>Chansons</h3>
    <div class="songs">
        $songText
    </div>
    <h3>Albums</h3>
    <div class="songs">
        $albumText
    </div>
HTML;


echo (new HTMLPage("Lowify - " . $artistName))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();