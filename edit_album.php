<?php
require 'db_config.php';  

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (isset($_POST['nazwa'], $_POST['nowa_nazwa'], $_POST['zespol'], $_POST['gatunek'], $_POST['rok'])) {
            $nazwa = $_POST['nazwa'];         
            $nowa_nazwa = $_POST['nowa_nazwa']; 
            $zespol = $_POST['zespol'];
            $gatunek = $_POST['gatunek'];
            $rok = $_POST['rok'];

            
            $updated_album = [
                'nazwa' => $nowa_nazwa,         
                'zespol' => $zespol,
                'gatunek' => $gatunek,
                'rok_wydania' => $rok
            ];

            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['nazwa' => $nazwa],  
                ['$set' => $updated_album], 
                ['multi' => false, 'upsert' => false] 
            );

            
            $manager->executeBulkWrite('Muzyka.Płyty', $bulk);

            echo "Płyta została zaktualizowana!<br>";

            
            $query = new MongoDB\Driver\Query(['nazwa' => $nowa_nazwa]); 
            $cursor = $manager->executeQuery('Muzyka.Płyty', $query);

            echo "Dane zapisane w bazie: <br>";
            foreach ($cursor as $document) {
                echo "Nazwa: " . $document->nazwa . "<br>";
                echo "Zespół: " . $document->zespol . "<br>";
                echo "Gatunek: " . $document->gatunek . "<br>";
                echo "Rok wydania: " . $document->rok_wydania . "<br>";
            }

        } else {
            echo "Brak wymaganych danych w formularzu!";
        }
    }
} catch (Throwable $e) {
    echo "Wystąpił problem: " . $e->getMessage();
}
?>
