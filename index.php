<?php
// เรียกใช้งาน PHPWord และโหลดไฟล์
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

if (isset($_POST['submit'])) {
    try {
        // โหลดไฟล์ที่อัพโหลด
        $phpWordFile = IOFactory::load($_FILES['document']['tmp_name']);

        // สร้างไฟล์ใหม่
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        foreach ($phpWordFile->getSections() as $sectionFile) {
            foreach ($sectionFile->getElements() as $element) {
                if ($element instanceof PhpOffice\PhpWord\Element\Text) {
                    $fontStyle = array('name' => 'Arial', 'size' => 12, 'bold' => true);
                    $alignStyle = array('align' => 'center');
                    $section->addText($element->getText(), $fontStyle, $alignStyle);
                }
            }
        }

        // บันทึกไฟล์ที่จัดรูปแบบ
        $phpWord->save('formatted_document.docx', 'Word2007');
        echo "Document formatted and saved successfully.";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="document" required />
    <button type="submit" name="submit">Upload and Format</button>
</form>
