<?php

class Utils
{
    /**
     * Permet de se connecter à la base de données, renvoie vers la page d'erreur "erreur.php" si la fonction échoue.
     * @return DatabaseManager L'objet permettant d'interagir avec la base de données.
     */
    public function connectDatabase(): DatabaseManager
    {
        try {
            $database = (new DatabaseManager(
                "mysql:host=mysql;dbname=lowify;charset=utf8mb4",
                "lowify",
                "lowifypassword"));
        } catch (PDOException $e) {
            header("Location: error.php?message=Impossible de se connecter à la base de données.");
            exit();
        }
        return $database;
    }

    /**
     * Permet de convertir une durée en secondes en minutes et secondes.
     * @param int $seconds La durée en secondes à convertir.
     * @return array L'array contenant le résultat de la conversion. [0] contient les minutes et [1] les secondes.
     */
    public function secondsToMin(int $seconds): array {
        $minutes = intdiv($seconds, 60);
        $seconds = $seconds % 60;
        return [$minutes, $seconds];
    }

    /**
     * @param string $date La date à formatter.
     * @return string La résultat du formattage (DD/MM/YYYY)
     */
    public function formatDate(string $date): string
    {
        // you can change this format using https://www.php.net/manual/en/datetime.format.php
        $format = "d/m/Y";
        try {
            $dateTimeObject = new DateTime($date);
        } catch (DateMalformedStringException $e) {
            return "[Format date incorrecte]";
        }
        return $dateTimeObject->format($format);
    }
}