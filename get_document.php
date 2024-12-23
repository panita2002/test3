<?php
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'test3');
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// อ่าน method จาก HTTP Request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // อัปโหลดเอกสาร
        if (isset($_FILES['file']) && isset($_POST['title'])) {
            $title = $_POST['title'];
            $description = $_POST['description'] ?? '';
            $file = $_FILES['file'];

            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = uniqid() . '-' . basename($file['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $stmt = $conn->prepare("INSERT INTO documents (title, description, created_at) VALUES (?, ?, NOW())");
                    $stmt->bind_param("ss", $title, $description);
                    if ($stmt->execute()) {
                        echo json_encode(["success" => true, "message" => "File uploaded", "file_path" => $filePath]);
                    } else {
                        echo json_encode(["error" => "Failed to save document record"]);
                    }
                    $stmt->close();
                } else {
                    echo json_encode(["error" => "Failed to move uploaded file"]);
                }
            } else {
                echo json_encode(["error" => "File upload error"]);
            }
        } else {
            echo json_encode(["error" => "Missing required fields"]);
        }
        break;

    case 'GET': // ดึงรายการเอกสารทั้งหมด
        $result = $conn->query("SELECT document_id, title, description, created_at FROM documents");
        $documents = [];
        while ($row = $result->fetch_assoc()) {
            $documents[] = $row;
        }
        echo json_encode($documents);
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

$conn->close();
?>
