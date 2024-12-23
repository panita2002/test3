<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;

try {
    // โหลดไฟล์ .docx
    $phpWord = IOFactory::load("AMD.docx");

    // ดึงข้อมูลจากไฟล์ .docx
    $text = "";
    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $element) {
            // ตรวจสอบว่า element เป็นประเภท Text หรือไม่
            if ($element instanceof PhpOffice\PhpWord\Element\Text) {
                // ตรวจสอบสไตล์ เช่น ตัวหนา ตัวเอียง ฯลฯ
                $fontStyle = $element->getFontStyle(); // ใช้ getFontStyle() แทน getStyle()
                $textContent = $element->getText();

                // ตรวจสอบลักษณะของฟอนต์ เช่น ตัวหนา ตัวเอียง
                if ($fontStyle && $fontStyle->isBold()) {
                    $textContent = "<strong>" . $textContent . "</strong>";
                }
                if ($fontStyle && $fontStyle->isItalic()) {
                    $textContent = "<em>" . $textContent . "</em>";
                }

                $text .= $textContent . "<br>";
            } elseif ($element instanceof PhpOffice\PhpWord\Element\TextRun) {
                // หากเป็น TextRun (ชุดข้อความหลายๆ ตัวที่มีลักษณะเดียวกัน)
                foreach ($element->getElements() as $subElement) {
                    if ($subElement instanceof PhpOffice\PhpWord\Element\Text) {
                        $fontStyle = $subElement->getFontStyle(); // ใช้ getFontStyle() แทน getStyle()
                        $textContent = $subElement->getText();

                        // ตรวจสอบลักษณะของฟอนต์
                        if ($fontStyle && $fontStyle->isBold()) {
                            $textContent = "<strong>" . $textContent . "</strong>";
                        }
                        if ($fontStyle && $fontStyle->isItalic()) {
                            $textContent = "<em>" . $textContent . "</em>";
                        }

                        $text .= $textContent . "<br>";
                    }
                }
            }
        }
    }

    // แสดงข้อความบนหน้าเว็บ
    echo "<div style='font-family: Arial, sans-serif; font-size: 14px;'>" . $text . "</div>";

} catch (Exception $e) {
    echo "Error loading file: " . $e->getMessage();
}
?>
