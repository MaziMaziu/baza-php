<?php
require 'db_config.php';  


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['zespol'])) {
        $zespol = $_POST['zespol'];

        try {
            
            $query = new MongoDB\Driver\Query(['zespol' => $zespol]);
            $cursor = $manager->executeQuery('Muzyka.Płyty', $query);

            
            echo "<h2>Albumy zespołu: $zespol</h2>";
            echo "<ul>";
            foreach ($cursor as $document) {
                echo "<li><strong>{$document->nazwa}</strong> (Rok: {$document->rok_wydania}, Gatunek: {$document->gatunek})</li>";
            }
            echo "</ul>";
        } catch (Throwable $e) {
            echo "Błąd podczas zapytania: " . $e->getMessage();
        }
    } else {
        echo "Proszę wprowadzić nazwę zespołu!";
    }
}
?>


<form action="find_album.php" method="POST">
    <label for="zespol">Nazwa zespołu:</label>
    <input type="text" name="zespol" id="zespol" required>
    <input type="submit" value="Znajdź albumy">
</form>
