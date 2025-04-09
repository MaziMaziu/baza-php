<?php
require 'db_config.php';

try {
   
    $albums = [
        ['nazwa' => 'The Dark Side of the Moon', 'zespol' => 'Pink Floyd', 'gatunek' => 'Progressive Rock', 'rok_wydania' => 1973],
        ['nazwa' => 'Abbey Road', 'zespol' => 'The Beatles', 'gatunek' => 'Rock', 'rok_wydania' => 1969],
        ['nazwa' => 'Led Zeppelin IV', 'zespol' => 'Led Zeppelin', 'gatunek' => 'Hard Rock', 'rok_wydania' => 1971],
        ['nazwa' => 'Back in Black', 'zespol' => 'AC/DC', 'gatunek' => 'Rock', 'rok_wydania' => 1980],
        ['nazwa' => 'Thriller', 'zespol' => 'Michael Jackson', 'gatunek' => 'Pop', 'rok_wydania' => 1982]
    ];

    
    $bulk = new MongoDB\Driver\BulkWrite;
    foreach ($albums as $album) {
        $bulk->insert($album);
    }

    
    $manager->executeBulkWrite('Muzyka.Płyty', $bulk);
    echo "5 płyt zostało dodanych do bazy danych.";
} catch (Throwable $e) {
    echo "Wystąpił problem: " . $e->getMessage();
}
?>
