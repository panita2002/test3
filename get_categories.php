<?php
// get_categories.php

$conn = new mysqli('localhost', 'root', '', 'test3');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT category_id, name FROM Categories");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

header('Content-Type: application/json');
echo json_encode($categories);
?>
