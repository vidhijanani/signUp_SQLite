<?php

try {
    // Create or open the SQLite database file
    $pdo = new PDO("sqlite:users.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the 'users' table if not exists
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            email TEXT NOT NULL,
            password TEXT NOT NULL
        )
    ";

    $pdo->exec($createTableSql);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Close the database connection
    $pdo = null;
}
?>
