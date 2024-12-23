<?php
// save_document.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'test3');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle uploaded file
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $content = $_POST['content']; // Content from the editor
    $uploaded_by = 1; // Assuming a logged-in user ID is 1

    // Save document
    $stmt = $conn->prepare("INSERT INTO Documents (title, description, category_id, created_by) VALUES (?, ?, ?, ?)");
    $description = substr(strip_tags($content), 0, 255); // Generate a short description
    $stmt->bind_param("ssii", $title, $description, $category_id, $uploaded_by);
    $stmt->execute();
    $document_id = $stmt->insert_id;

    // Save document version
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $filePath = "uploads/" . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);

        $stmt = $conn->prepare("INSERT INTO DocumentVersions (document_id, version_number, file_path, file_type, file_size, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
        $version_number = "1.0";
        $fileType = $file['type'];
        $fileSize = $file['size'];
        $stmt->bind_param("isssii", $document_id, $version_number, $filePath, $fileType, $fileSize, $uploaded_by);
        $stmt->execute();
    }

    echo "Document saved successfully!";
}
?>
