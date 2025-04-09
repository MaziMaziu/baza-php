<?php
require 'db_config.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id']; 

        try {
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id)]);  

            
            $manager->executeBulkWrite('Muzyka.Płyty', $bulk);
            echo "Płyta została usunięta!";
        } catch (Throwable $e) {
            echo "Błąd podczas usuwania: " . $e->getMessage();
        }
    }
}


$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('Muzyka.Płyty', $query);

echo "<h2>Lista płyt</h2>";
echo "<ul>";
foreach ($cursor as $document) {
    echo "<li>
            <strong>{$document->nazwa}</strong> 
            (Zespół: {$document->zespol}, Rok: {$document->rok_wydania}, Gatunek: {$document->gatunek}) 
            <a href='edit_album.php?id={$document->_id}'>Edytuj</a> | 
            <a href='delete_album.php?id={$document->_id}'>Usuń</a>
          </li>";
}
echo "</ul>";
?>
