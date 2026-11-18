<?php

// Define the database directory and file path
$databaseDir = __DIR__ . '/db';
$databaseFile = $databaseDir . '/recipe_db.db';

// Check if the database directory exists, if not create it
if (!is_dir($databaseDir)) {
    if (!mkdir($databaseDir, 0777, true)) {
        die('Failed to create folders: ' . $databaseDir);
    }
}

// Define the database file constant
if (!defined('DB_FILE')) define('DB_FILE', $databaseFile);

// User-defined function for md5
function my_udf_md5($string) {
    return md5($string);
}

class DBConnection extends SQLite3 {
    protected $db;

    function __construct() {
        try {
            // Attempt to open the database
            $this->open(DB_FILE);

            // Create the custom MD5 function
            $this->createFunction('md5', 'my_udf_md5');
            $this->exec("PRAGMA foreign_keys = ON;");

            // Execute the table creation queries
            $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
                `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `fullname` TEXT NOT NULL,
                `username` TEXT NOT NULL,
                `password` TEXT NOT NULL,
                `type` INTEGER NOT NULL Default 1,
                `status` INTEGER NOT NULL Default 1,
                `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $this->exec("CREATE TABLE IF NOT EXISTS `category_list` (
                `category_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `name` TEXT NOT NULL,
                `description` TEXT NOT NULL 
            )");

            $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
                `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `fullname` TEXT NOT NULL,
                `username` TEXT NOT NULL,
                `password` TEXT NOT NULL,
                `status` INTEGER NOT NULL Default 1,
                `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $this->exec("CREATE TABLE IF NOT EXISTS `recipe_list` (
                `recipe_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `category_id` INTEGER NOT NULL,
                `user_id` INTEGER NOT NULL,
                `title` TEXT NOT NULL,
                `description` TEXT NOT NULL,
                `ingredients` TEXT NOT NULL,
                `step` TEXT NOT NULL,
                `other_info` TEXT NOT NULL,
                `status` INTEGER NOT NULL,
                `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(`category_id`) REFERENCES `category_list`(`category_id`) ON DELETE CASCADE,
                FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE
            )");

            $this->exec("CREATE TABLE IF NOT EXISTS `comment_list` (
                `comment_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `recipe_id` TEXT NOT NULL,
                `user_id` TEXT NOT NULL,
                `message` TEXT NOT NULL,
                `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(`recipe_id`) REFERENCES `recipe_list`(`recipe_id`) ON DELETE CASCADE,
                FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE
            )");

            $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1, 'Administrator', 'admin', md5('admin123'), 1, 1, CURRENT_TIMESTAMP)");
            $this->exec("INSERT or IGNORE INTO `user_list` VALUES (1, 'Try My Recipe Mgt', 'mgt', md5('mgt123'), 1, CURRENT_TIMESTAMP)");
        } catch (Exception $e) {
            die('Exception caught: ' . $e->getMessage());
        }
    }

    function __destruct() {
        $this->close();
    }
}

// Create a new database connection
$conn = new DBConnection();
?>
