<?php

require_once 'inc/page.inc.php';

$message = $_GET["message"] ?? "Erreur Inconnue";

$html = <<<HTML
    <a href="index.php"><h1>$message</h1></a>
    <a href="index.php"><h2 class="block">Retour à l'accueil</h2></a>
HTML;

echo (new HTMLPage("Lowify - Erreur"))
    ->addStylesheet("style.css")
    ->addContent($html)
    ->render();