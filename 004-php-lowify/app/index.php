<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once 'inc/utils.php';

$utils = (new Utils());
$database = $utils->connectDatabase();

try {
    $topArtists = $database->executeQuery("
        SELECT * FROM artist
        ORDER BY artist.monthly_listeners DESC
        LIMIT 5
    ");
    $recentAlbums = $database->executeQuery("
        SELECT * FROM album
        ORDER BY album.release_date DESC
        LIMIT 5
    ");
    $topAlbums = $database->executeQuery("
        SELECT AVG(song.note) note, song.album_id, album.name, album.cover FROM song
        INNER JOIN album
        WHERE album_id = album.id
        GROUP BY song.album_id
        ORDER BY note DESC
        LIMIT 5
    ");
} catch (PDOException $e) {
    header("Location: error.php?message=Impossible de trouver toutes les informations dans la base de données.");
    exit();
}

$formattedArtists = "";
foreach($topArtists as $artist) {
    $formattedArtists = $formattedArtists . "<div class='block'>";
    $formattedArtists = $formattedArtists . "<h2>" . $artist["name"] . "</h2>";
    $formattedArtists = $formattedArtists . "<img src=\"" . $artist["cover"] . "\">";
    $formattedArtists = $formattedArtists . "</div>";
}

$formattedRecentAlbums = "";
//On attache toutes les informations nécessaires dans un seul string
foreach($recentAlbums as $recentAlbum) {
    $albumId = $recentAlbum["id"];
    $date = $utils->formatDate($recentAlbum["release_date"]);
    $formattedRecentAlbums = $formattedRecentAlbums . "<a href='album.php?album=$albumId'><div class='block'>";
    $formattedRecentAlbums = $formattedRecentAlbums . "<h2>" . $recentAlbum["name"] . "</h2>";
    $formattedRecentAlbums = $formattedRecentAlbums . "<p>Date de Sortie : " . $date . "</p>";
    $formattedRecentAlbums = $formattedRecentAlbums . "<img src=\"" . $recentAlbum["cover"] . "\"/>";
    $formattedRecentAlbums = $formattedRecentAlbums . "</div></a>";
}

$formattedTopAlbums = "";
//On attache toutes les informations nécessaires dans un seul string
foreach($topAlbums as $topAlbum) {
    $albumId = $topAlbum["album_id"];
    $averageRating = number_format($topAlbum["note"],2, ",");
    $formattedTopAlbums = $formattedTopAlbums . "<a href='album.php?album=$albumId'><div class='block'>";
    $formattedTopAlbums = $formattedTopAlbums . "<h2>" . $topAlbum["name"] . "</h2>";
    $formattedTopAlbums = $formattedTopAlbums . "<p>Note Moyenne : " . $averageRating . "</p>";
    $formattedTopAlbums = $formattedTopAlbums . "<img src=\"" . $topAlbum["cover"] . "\"/>";
    $formattedTopAlbums = $formattedTopAlbums . "</div></a>";
}

$html = <<<HTML
    <h1>Lowify</h1>
    <h3>Top Artistes</h3>
    <div class="songs">
        $formattedArtists
    </div>
    <h3>Top Sorties</h3>
    <div class="songs">
        $formattedRecentAlbums
    </div>
    <h3>Top Albums</h3>
    <div class="songs">
        $formattedTopAlbums
    </div>
HTML;

echo (new HTMLPage("Lowify - Accueil"))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();