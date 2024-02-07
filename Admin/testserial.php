<?php
require_once 'connect.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่าหมวดหมู่มาจากฟอร์มหรือไม่
if(isset($_POST['item_category'])) {
    // รับค่าหมวดหมู่ที่เลือก
    $selectedCategory = $_POST['item_category'];

    // ดึงข้อมูล Serialnumber ล่าสุดของหมวดหมู่ที่เลือก
    $stmt = $conn->prepare("SELECT MAX(SUBSTRING(item_sn, -2)) AS max_serial FROM item WHERE cate_id = :selectedCategory");
    $stmt->bindParam(':selectedCategory', $selectedCategory, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxSerial = $row['max_serial'];

    // หากไม่มี Serialnumber ในหมวดหมู่ที่เลือก ให้เริ่มที่ 01
    $newSerial = ($maxSerial !== null) ? sprintf('%02d', $maxSerial + 1) : '01';

    // แสดงฟอร์ม
    echo '<div class="form-group">
            <div class="col-sm-5">
                <p>Status:</p>
                <select name="item_category" class="form-control" required>
                    <!-- ตัวเลือกของหมวดหมู่ -->
                </select>
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-sm-8">
                <p> Serialnumber </p>
                <input type="text" name="item_sn" class="form-control" required placeholder="Serialnumber" value="' . $selectedCategory . $newSerial . '" />
            </div>
          </div>';
}
?>
