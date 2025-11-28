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
    header("Location: error.php?message=Impossible de se connecter à la base de données.");
    exit();
}

try {
    $artists = $database->executeQuery("SELECT * FROM artist WHERE id = $artist");
    $songs = $database->executeQuery("
        SELECT song.name, song.id, song.note, song.duration, album.cover FROM song     
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

if(sizeof($artists) == 0) {
    header("Location: error.php?message=Artiste Introuvable");
    exit();
}

$artist = $artists[0];
$artistName = $artist["name"];
$artistCover = $artist["cover"];
$artistBiography = $artist["biography"];

$songText = "";

foreach($songs as $song) {
    $duration = calculateSongDuration($song["duration"]);
    $minutes = $duration[0];
    $seconds = $duration[1];
    $songText = $songText . "<div class='block'>";
    $songText = $songText . "<h2>" . $song["name"] . "</h2>";
    $songText = $songText . "<p>Note : " . $song["note"] . " | Durée : " . $minutes . ":" . $seconds . "</p>";
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

function calculateSongDuration(int $duration): array {
    $minutes = intdiv($duration, 60);
    $seconds = $duration % 60;
    return [$minutes, $seconds];
}

function calculateViewers(int $viewers): string {
    $formattedViews = "$viewers";
    if($viewers >= 1000000) {
        $float = $viewers / 1000000;
        $formattedViews = number_format($float, 1) . " M";
    }
    else if($viewers >= 1000) {
        $float = $viewers / 1000;
        $formattedViews = number_format($float, 2) . " K";
    }
    return $formattedViews;
}

$viewers = calculateViewers($artist["monthly_listeners"]);

$html = <<<HTML
    <h1>$artistName</h1>
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


echo (new HTMLPage("Lowify - " . $artistName))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();