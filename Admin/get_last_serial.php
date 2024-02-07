<?php
require_once 'connect.php';

if (isset($_GET['Cate_id'])) {
    $cateId = $_GET['Cate_id'];

    // ดึง Serial number ล่าสุดของหมวดหมู่ที่เลือก
    $stmt = $conn->prepare("SELECT LPAD(MAX(SUBSTRING(item_serial, CHAR_LENGTH(:cateId) + CHAR_LENGTH(item_id) + 1)) + 1, 
    CHAR_LENGTH(:cateId) + CHAR_LENGTH(item_id), '0') AS max_serial FROM item WHERE Cate_id  = :cateId");
    $stmt->bindParam(':cateId', $cateId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxSerial = $row['max_serial'];

    // ส่งค่าลำดับ Serial number ล่าสุดกลับ
    echo ($maxSerial !== null) ? $maxSerial : '0001';
} else {
    // หากไม่ได้รับพารามิเตอร์ cate_id
    echo 'Error: Missing cate_id parameter';
}
?>






