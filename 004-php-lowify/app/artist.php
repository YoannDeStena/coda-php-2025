<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';

$artist = $_GET["artist"] ?? "error404";

try {

    $database = (new DatabaseManager(
        "mysql:host=mysql;dbname=lowify;charset=utf8mb4",
        "lowify",
        "lowifypassword"));
} catch (Exception $e) {
    $message = $e->getMessage();
    echo (new HTMLPage("Lowify - Error"))
        ->addStylesheet("style.css")
        ->addContent("<h1>$message</h1>")
        ->render();
    return;
}

$artists = $database->executeQuery("SELECT * FROM artist WHERE id = $artist");

if(sizeof($artists) == 0) {
    echo (new HTMLPage("Lowify - Error 404"))
        ->addStylesheet("style.css")
        ->addContent("<h1>Artiste introuvable.</h1>")
        ->render();
    return;
}

$artist = $artists[0];
$artistName = $artist["name"];

$html = <<<HTML
    <h1>$artistName</h1>
HTML;


echo (new HTMLPage("Lowify - " . $artistName))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();