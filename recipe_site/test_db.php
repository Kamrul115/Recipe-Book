<?php
$databaseDir = __DIR__ . '/db';
$databaseFile = $databaseDir . '/test_db.db';

if (!is_dir($databaseDir)) {
    if (!mkdir($databaseDir, 0777, true)) {
        die('Failed to create folders: ' . $databaseDir);
    } else {
        echo 'Directory created successfully: ' . $databaseDir . '<br>';
    }
} else {
    echo 'Directory already exists: ' . $databaseDir . '<br>';
}

if (!defined('DB_FILE')) define('DB_FILE', $databaseFile);

try {
    $db = new SQLite3(DB_FILE);
    echo 'Database file opened/created successfully: ' . DB_FILE . '<br>';
} catch (Exception $e) {
    die('Exception caught: ' . $e->getMessage());
}
?>
