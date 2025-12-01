<?php

class Utils
{
    /**
     * Permet de se connecter à la base de données, renvoie vers la page d'erreur "erreur.php" si la fonction échoue.
     * @return DatabaseManager L'objet permettant d'interagir avec la base de données.
     */
    public function connectDatabase(): DatabaseManager {
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
     * Permet de formatter une durée en secondes en minutes et secondes au format mm:ss.
     * @param int $seconds La durée en secondes à formatter.
     * @return string Le résultat du formattage (mm:ss)
     */
    public function formatDuration(int $seconds): string {
        $minutes = intdiv($seconds, 60);
        $seconds = $seconds % 60;
        return (new DateTime())->setTime(0, $minutes, $seconds)->format("i:s");
    }

    /**
     * @param string $date La date à formatter.
     * @return string La résultat du formattage (DD/MM/YYYY)
     */
    public function formatDate(string $date): string {
        // you can change this format using https://www.php.net/manual/en/datetime.format.php
        $format = "d/m/Y";
        try {
            $dateTimeObject = new DateTime($date);
        } catch (DateMalformedStringException $e) {
            return "[Format date incorrecte]";
        }
        return $dateTimeObject->format($format);
    }

    /**
     * Permet de transformer un nombre en format plus lisible (1M, 1K...)
     * @param int $viewers Le nombre à formatter.
     * @return string Le résultat du formattage.
     */
    public function formatViewers(int $viewers): string {
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
}