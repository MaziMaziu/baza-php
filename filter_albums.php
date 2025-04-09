<?php
require 'db_config.php'; // Import konfiguracji bazy danych

// Pobieranie danych z formularza
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

$albums = [];
if (!empty($genre)) {
    try {
        // Przygotowanie zapytania do MongoDB
        $query = new MongoDB\Driver\Query(['genre' => $genre]);
        $cursor = $manager->executeQuery('your_database_name.albums', $query);

        // Pobieranie wyników
        foreach ($cursor as $document) {
            $albums[] = $document;
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        die("Błąd podczas pobierania danych: " . $e->getMessage());
    }
}
?>