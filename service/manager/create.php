<?php

require_once '../connect.php';
/**
 |--------------------------------------------------------------------------
 | เขียนโค้ด Insert Admin SQL ตัวอย่าง
 | 'INSERT INTO admin (field1, field2, field3) VALUES (:var1, :var2, :var3)'
 |--------------------------------------------------------------------------
*/

echo "<pre>";
echo "ข้อมูลจากที่ได้จาก form \n";
print_r($_POST);
echo "ข้อมูลจากที่ได้จาก การอัพโหลดรูปภาพ \n";
print_r($_FILES);
echo "</pre>";

echo "<a href='../../pages/manager/'> กลับหน้าเดิม </a>"

?>