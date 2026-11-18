<?php

// Include your DBConnection.php script
require_once 'DBConnection.php';

// Test insert functionality
$result = $conn->exec("INSERT INTO recipe_list (category_id, user_id, title, description, ingredients, step, other_info, status) VALUES (1, 1, 'Test Title', 'Test Description', 'Test Ingredients', 'Test Step', 'Test Other Info', 1)");

if ($result) {
    echo "Insert successful!";
} else {
    echo "Insert failed!";
}
