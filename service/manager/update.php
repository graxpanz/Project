<?php

require_once '../connect.php';
/**
 |--------------------------------------------------------------------------
 | เขียนโค้ด Update Admin SQL ตัวอย่าง
 | 'UPDATE admin SET field1 = :var1, field2= :var2 WHERE admin_id = :id "'
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