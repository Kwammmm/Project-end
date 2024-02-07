<?php
require_once 'connect.php';

if (isset($_GET['Cate_Name'])) {
    $Cate_Name = $_GET['Cate_Name'];

    // SQL query สำหรับลบข้อมูล
    $sql = "DELETE FROM category WHERE Cate_Name = ?";
    
    // ใช้ prepare statement
    $stmt = $conn->prepare($sql);

    // ผูกค่าพารามิเตอร์
    $stmt->bind_param('s', $Cate_Name);

    // ทำการ execute
    if ($stmt->execute()) {
        // ลบข้อมูลสำเร็จ
        echo json_encode(['status' => 'success']);
    } else {
        // เกิดข้อผิดพลาดในการ execute
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    // ปิด statement
    $stmt->close();

    // ปิดการเชื่อมต่อ
    $conn->close();
} else {
    // ถ้าไม่ได้รับ Category Name มา
    echo json_encode(['status' => 'error', 'message' => 'Category Name not provided']);
}
?>
