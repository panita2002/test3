<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test3');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT document_id, title, description, created_at FROM documents");
if ($result->num_rows > 0) {
    echo "<h1>Document List</h1>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>";
        echo "<strong>" . htmlspecialchars($row['title']) . "</strong><br>";
        echo htmlspecialchars($row['description']) . "<br>";
        echo "Uploaded at: " . $row['created_at'] . "<br>";
        echo "</p>";
    }
} else {
    echo "No documents found.";
}

$conn->close();
?>
