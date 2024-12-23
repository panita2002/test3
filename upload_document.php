<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test3');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าไฟล์ถูกอัปโหลดมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $file = $_FILES['file'];

    // ตรวจสอบว่าไฟล์อัปโหลดสำเร็จ
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // โฟลเดอร์เก็บไฟล์
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $filePath = $uploadDir . uniqid() . '-' . $fileName;

        // ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // บันทึกข้อมูลไฟล์ลงในฐานข้อมูล
            $stmt = $conn->prepare("INSERT INTO documents (title, description, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $title, $description);

            if ($stmt->execute()) {
                echo "File uploaded and record saved successfully!";
            } else {
                echo "Error saving record: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error moving uploaded file.";
        }
    } else {
        echo "File upload error: " . $file['error'];
    }
} else {
    echo "No file uploaded.";
}

$conn->close();
?>
