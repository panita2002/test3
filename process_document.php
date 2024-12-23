<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();
$section = $phpWord->addSection();

try {
    // โหลดไฟล์ .docx ที่จะจัดรูปแบบ
    $phpWordFile = IOFactory::load("AMD.docx");

    // ดึงข้อความจากไฟล์และจัดการรูปแบบ
    foreach ($phpWordFile->getSections() as $sectionFile) {
        foreach ($sectionFile->getElements() as $element) {
            if ($element instanceof PhpOffice\PhpWord\Element\Text) {
                // เพิ่มข้อความที่ดึงจากไฟล์พร้อมกับการจัดรูปแบบ
                $fontStyle = array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '000000');
                $section->addText('This is a sample text with custom font style.', $fontStyle);
                $alignStyle = array('align' => 'center');
                // ตั้งค่าฟอนต์ และขนาดฟอนต์
                // กำหนดการจัดข้อความ (Align)
                $alignStyle = array('align' => 'center');
                $section->addText('This text is centered.', $fontStyle, $alignStyle);
            }
        }
    }

    // บันทึกเอกสารที่จัดรูปแบบใหม่
    $phpWord->save('formatted_document.docx', 'Word2007');
} catch (Exception $e) {
    echo "Error loading file: " . $e->getMessage();
}

?>
