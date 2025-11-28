<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';
require_once 'inc/utils.php';

$query = $_GET["query"] ?? "error404";

$utils = (new Utils());
$database = $utils->connectDatabase();

$foundArtists = $database->executeQuery("
    SELECT * FROM artist
    WHERE
        (
            MATCH(name) AGAINST('$query' IN NATURAL LANGUAGE MODE) OR
            name LIKE '$query'
        )
");
$foundAlbums = $database->executeQuery("
    SELECT album.id, album.name, album.cover, album.release_date, artist.name artist_name FROM album
    INNER JOIN artist
    WHERE
        (
            MATCH(album.name) AGAINST('$query' IN NATURAL LANGUAGE MODE) OR
            album.name LIKE '$query'
        )
        AND album.artist_id = artist.id
");
$foundSongs = $database->executeQuery("
    SELECT song.name, song.duration, song.note, artist.name artist_name, album.name album_name, album.cover FROM song
    INNER JOIN artist, album
    WHERE
        (
            MATCH(song.name) AGAINST('$query' IN NATURAL LANGUAGE MODE) OR
            song.name LIKE '$query'
        )
        AND song.artist_id = artist.id AND song.album_id = album.id
");

//On vérifie s'il y a un résultat.
$errorMessage = "";
if(sizeof($foundArtists) == 0 && sizeof($foundAlbums) == 0 && sizeof($foundSongs) == 0) {
    $errorMessage = "<h3>Aucun résultat</h3>";
}

//On attache toutes les informations nécessaires dans un seul string
$searchResult = "";
foreach($foundArtists as $artist) {
    $artistId = $artist["id"];
    $viewers = $utils->formatViewers($artist["monthly_listeners"]);
    $searchResult = $searchResult . "<a href='artist.php?artist=$artistId'><div class='block'>";
    $searchResult = $searchResult . "<h2>" . $artist["name"] . " (Artiste)</h2>";
    $searchResult = $searchResult . "<p>Auditeurs Mensuels : $viewers</p>";
    $searchResult = $searchResult . "<img src=\"" . $artist["cover"] . "\">";
    $searchResult = $searchResult . "</div></a>";
}

//On attache toutes les informations nécessaires dans un seul string
foreach($foundAlbums as $album) {
    $albumId = $album["id"];
    $date = $utils->formatDate($album["release_date"]);
    $artistName = $album["artist_name"];
    $searchResult = $searchResult . "<a href='album.php?album=$albumId'><div class='block'>";
    $searchResult = $searchResult . "<h2>" . $album["name"] . " (Album)</h2>";
    $searchResult = $searchResult . "<p>Date de Sortie : $date | De $artistName</p>";
    $searchResult = $searchResult . "<img src=\"" . $album["cover"] . "\"/>";
    $searchResult = $searchResult . "</div></a>";
}

//On attache toutes les informations nécessaires dans un seul string
foreach($foundSongs as $song) {
    $duration = $utils->secondsToMin($song["duration"]);
    $minutes = $duration[0];
    $seconds = $duration[1];
    $searchResult = $searchResult . "<div class='block'>";
    $searchResult = $searchResult . "<h2>" . $song["name"] . " (Titre)</h2>";
    $searchResult = $searchResult . "<p>Note : " . $song["note"] . " | Durée : $minutes:$seconds</p>";
    $searchResult = $searchResult . "<br><p>De <b>" . $song["artist_name"] . "</b> dans l'album <b>" . $song["album_name"] . "</b></p>";
    $searchResult = $searchResult . "<img src=\"" . $song["cover"] . "\"/>";
    $searchResult = $searchResult . "</div>";
}

$html = <<<HTML
    <h1>Lowify</h1>
    $errorMessage
    <div class="songs">
        $searchResult
    </div>
HTML;

//On envoie la page au client
echo (new HTMLPage("Lowify - Recherche ($query)"))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();