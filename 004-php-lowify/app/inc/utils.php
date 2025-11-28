<?php

class Utils
{
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

    public function secondsToMin(int $seconds): array {
        $minutes = intdiv($seconds, 60);
        $seconds = $seconds % 60;
        return [$minutes, $seconds];
    }
}