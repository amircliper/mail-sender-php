<?php
try {
    $db = new PDO('sqlite:users.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS users (
                            id INTEGER PRIMARY KEY,
                            name TEXT,
                            phone TEXT,
                            email TEXT,
                            description TEXT
                        )");

    echo "Database and table created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
