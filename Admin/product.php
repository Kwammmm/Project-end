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


<?php include('testmenu.php'); ?>
<?php
// Include database connection
require_once 'connect.php';

// ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
if(isset($_POST['filter_item'])){
    $filteritem = $_POST['filter_item'];

    // ใช้ WHERE clause ใน SQL เพื่อกรองข้อมูลตามสถานะที่ถูกเลือก
    $stmt = $conn->prepare("SELECT item_id, item_name, item_detail, item_category, item_serial, item_owner, item_status FROM item WHERE item_status = :item_status");
$stmt->bindParam(':item_status', $filteritem, PDO::PARAM_STR);
$stmt->execute();
$filtereditem = $stmt->fetchAll(PDO::FETCH_ASSOC);


} else {
    // ถ้าไม่มีการกรอง ดึงข้อมูลทั้งหมด
    $stmt = $conn->prepare("SELECT item_id, item_name, item_detail, item_category, item_serial, item_owner, item_status FROM item");
    $stmt->execute();

    $filtereditem = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// เพิ่มเงื่อนไขเพื่อตรวจสอบการส่งค่ามาเพื่อล้างการกรอง
if(isset($_POST['clear_filter'])){
    // ส่งค่าว่างเปล่ากลับไป
    header("Location: product.php");
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
            width: 75%;
            margin-left: 20%;
            margin-right: auto;
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
        h1{
            margin-left: 20%;
            margin-top: 5%;
        }
    
        form{
            padding: 15px;
            margin-left: 19%;
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
    <h1>Product list <a href="Add_Product.php" class="btn btn-info">+Add Member</a> </h1>

    <!-- ส่วนของฟอร์มสำหรับกรองข้อมูล -->
    

    <table>
        <thead>
            <tr>
                <th width="7%">ID</th>
                <th width="15%">Property</th>
                <th width="15%">Detail</th>
                <th width="15%">category</th>
                <th width="20%">serialnumber</th>
                <th width="10%">owner</th>
                <th width="10%">status</th>
                <th width="5%">Edit</th>
                <th width="5%">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtereditem as $item): ?>
                <tr>
                    <td><?= $item['item_id']; ?></td>
                    <td><?= $item['item_name']; ?></td>
                    <td><?= $item['item_detail']; ?></td>
                    <td><?= $item['item_category']; ?></td>
                    <td><?= $item['item_serial']; ?></td> 
                    <td><?= $item['item_owner'];?></td>
                    <td><?= $item['item_status'];?></td>
                    <td><a href="memberEdit.php?id=<?= $k['item_id'];?>" class="btn btn-warning btn-sm">แก้ไข</a></td>
                    <td><a href="Delmember.php?id=<?= $k['item_id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                    </tr>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>