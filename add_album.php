<?php
require 'db_config.php';  

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (isset($_POST['nazwa'], $_POST['zespol'], $_POST['gatunek'], $_POST['rok'])) {
            
            
            $nazwa = htmlspecialchars(trim($_POST['nazwa']));
            $zespol = htmlspecialchars(trim($_POST['zespol']));
            $gatunek = htmlspecialchars(trim($_POST['gatunek']));
            $rok = trim($_POST['rok']);

            
            $errors = [];

            
            if (empty($nazwa)) {
                $errors[] = "Nazwa płyty jest wymagana.";
            }

            
            if (empty($zespol)) {
                $errors[] = "Zespół jest wymagany.";
            }

            
            if (empty($gatunek)) {
                $errors[] = "Gatunek jest wymagany.";
            }

            
            if (empty($rok) || !is_numeric($rok) || $rok < 1900 || $rok > date("Y")) {
                $errors[] = "Rok wydania musi być liczbą z zakresu od 1900 do bieżącego roku.";
            }

            
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
                exit;  
            }

            
            $album = [
                'nazwa' => $nazwa,
                'zespol' => $zespol,
                'gatunek' => $gatunek,
                'rok_wydania' => (int) $rok 
            ];

            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($album);

            $manager->executeBulkWrite('Muzyka.Płyty', $bulk);

            echo "Płyta została dodana do bazy danych!<br>";

            // Potwierdzenie zapisania danych
            $query = new MongoDB\Driver\Query(['nazwa' => $nazwa]); 
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
