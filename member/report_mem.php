<?php
session_start();
echo '
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
//เช็คว่ามีตัวแปร session อะไรบ้าง
//print_r($_SESSION);
//exit();
//สร้างเงื่อนไขตรวจสอบสิทธิ์การเข้าใช้งานจาก session
if(empty($_SESSION['member_id']) && empty($_SESSION['member_name']) && empty($_SESSION['member_sername'])){
            echo '<script>
                setTimeout(function() {
                swal({
                title: "คุณไม่มีสิทธิ์ใช้งานหน้านี้",
                type: "error"
                }, function() {
                window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                });
                }, 1000);
                </script>';
            exit();
}

?>

<?php include('menu_mem.php'); ?>

<?php
// Include database connection
require_once 'connect.php';

// ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
if(isset($_POST['filteritem_status'])){
    $filteritemStatus = $_POST['filteritem_status'];

    // ใช้ WHERE clause ใน SQL เพื่อกรองข้อมูลตามสถานะที่ถูกเลือก
    $stmt = $conn->prepare("SELECT item_id item_name, item_category, item_sn, item_number ,item_owner ,item_status  FROM item WHERE item_status  = :item_status ");
    $stmt->bindParam(':filteritemStatus', $filteritemStatus, PDO::PARAM_STR);
    $stmt->execute();

    $filteritemStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // ถ้าไม่มีการกรอง ดึงข้อมูลทั้งหมด
    $stmt = $conn->prepare("SELECT item_id, item_name, item_category, item_sn, item_number, item_owner,item_status FROM item");
    $stmt->execute();

    $filteritemStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// เพิ่มเงื่อนไขเพื่อตรวจสอบการส่งค่ามาเพื่อล้างการกรอง
if(isset($_POST['clear_filter'])){
    // ส่งค่าว่างเปล่ากลับไป
    header("Location: Member.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property management system</title>
    
    <style>
        body {
            font-family: 'Tahoma', Arial, sans-serif;
        }
        table {
            width: 1100px;
            margin-left: 0%;
            margin-right: auto;
            margin-top: 1.3%;
            padding-top: 10%;
            border-collapse: collapse;
            background-color: #f2f2f2;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th{
            background-color: #A4A4A4;
            font-size: 20px;
            text-align: center;
        }
        h1{
            margin-top: 7%;
            color: white;
            text-align: center;
            padding: 0.6%
        }
    
        form{
            padding: 15px;
            margin-left: 5%;
        }

        
        .btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 15px;
    cursor: pointer;
    text-align: center;	
    text-decoration: none;
    outline: none;
    color: #fff;
    background-color: #4CAF50;
    border: none;
    border-radius: 15px;
    box-shadow: 0 9px #999;
}

.button:hover {background-color: #3e8e41}

.button:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}
    </style>
</head>
<body>
<div style="width: 1217px; height: 60px; background: #6D7BC8; border-radius: 4px ; margin-left: 19%;">
<div style="color: black; font-size: 20px; font-family: Inter; font-weight: 600; word-wrap: break-word ;">
    <h1>Balance Report</h1>
</div>
</div>

<div style="width: 1217px; height: auto; background: #FFFFFF; border: 1px solid black; border-radius: 4px;margin-left: 19%;">
    <form method="post">
        <label for="search">Search: </label>
        <input type="text" name="search" id="search" placeholder="ป้อนชื่อหรือนามสกุล">
        <button type="submit">search</button>

    <table>
        <thead>
            <tr>
                <th width="7%">ID</th>
                <th width="15%">name</th>
                <th width="15%">category</th>
                <th width="20%">serialnumber</th>
                <th width="15%">number</th>
                <th width="10%">owner</th>
                <th width="10%">status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteritemStatus as $item): ?>
                <tr>
                    <td><?= $item['item_id']; ?></td>
                    <td><?= $item['item_name']; ?></td>
                    <td><?= $item['item_category']; ?></td>
                    <td><?= $item['item_sn']; ?></td>
                    <td><?= $item['item_number'];?></td>
                    <td><?= $item['item_owner'];?></td>
                    <td><button style="color: <?= getColor($item['item_status']); ?>;"><?= $item['item_status']; ?></button></td>
                    
                    </tr>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
                    function getColor($status) {
                        switch ($status) {
                            case 'กำลังยืม':
                        return 'red';
                            case 'คืนแล้ว':
                        return 'black';
                            case 'มีให้ยืม':
                        return 'green';
                    default:
            return 'black'; // หรือสีที่คุณต้องการสำหรับสถานะอื่น ๆ
    }
}
?>
</div>
</body>
</html>


