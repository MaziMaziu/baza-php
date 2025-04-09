<?php
require 'db_config.php'; // Załaduj konfigurację połączenia

// Pobierz gatunek z formularza lub z query string
$gatunek = isset($_POST['gatunek']) ? $_POST['gatunek'] : (isset($_GET['gatunek']) ? $_GET['gatunek'] : null);

if ($gatunek) {
    $albumsPerPage = 5;  // Liczba albumów na stronie

    // Pobierz numer strony z query string, jeśli nie ma, ustaw na 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $skip = ($page - 1) * $albumsPerPage;  // Ilość albumów do pominięcia

    try {
        // Tworzenie zapytania do bazy
        $query = new MongoDB\Driver\Query(
            ['gatunek' => $gatunek],
            [
                'skip' => $skip,  // Pomijanie wyników dla poprzednich stron
                'limit' => $albumsPerPage  // Maksymalna liczba albumów na stronie
            ]
        );

        // Wykonanie zapytania do kolekcji 'Płyty' w bazie 'Muzyka'
        $cursor = $manager->executeQuery('Muzyka.Płyty', $query);

        // Sprawdzanie, czy znaleziono jakiekolwiek albumy
        $albums = iterator_to_array($cursor);

        if (count($albums) > 0) {
            echo "<h1>Albumy gatunku: " . htmlspecialchars($gatunek) . "</h1>";
            echo "<ul>";
            foreach ($albums as $album) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($album->nazwa) . "</strong><br>";
                echo "Zespół: " . htmlspecialchars($album->zespol) . "<br>";
                echo "Rok wydania: " . htmlspecialchars($album->rok_wydania) . "<br>";
                echo "Gatunek: " . htmlspecialchars($album->gatunek) . "<br><br>";
                echo "</li>";
            }
            echo "</ul>";

            // Zliczanie wszystkich albumów tego gatunku
            $countQuery = new MongoDB\Driver\Query(['gatunek' => $gatunek]);
            $countCursor = $manager->executeQuery('Muzyka.Płyty', $countQuery);
            $totalAlbums = iterator_count($countCursor);  // Zliczanie wszystkich pasujących albumów
            $totalPages = ceil($totalAlbums / $albumsPerPage);  // Obliczanie liczby stron

            // Wyświetlanie przycisków stronnicowania
            if ($totalPages > 1) {
                echo "<div class='pagination'>";
                if ($page > 1) {
                    echo "<a href='?page=" . ($page - 1) . "&gatunek=" . urlencode($gatunek) . "'>Poprzednia</a> ";
                }
                if ($page < $totalPages) {
                    echo "<a href='?page=" . ($page + 1) . "&gatunek=" . urlencode($gatunek) . "'>Następna</a>";
                }
                echo "</div>";
            }

        } else {
            echo "<p>Brak albumów w wybranym gatunku.</p>";
        }

    } catch (Throwable $e) {
        echo "Wystąpił problem z połączeniem: " . $e->getMessage();
    }
} else {
    echo "<p>Proszę wybrać gatunek z formularza.</p>";
}
?>