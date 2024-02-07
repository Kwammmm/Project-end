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
<?php
// Include database connection
require_once 'connect.php';

// ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
if(isset($_POST['filter_status'])){
    $filterStatus = $_POST['filter_status'];

    // ใช้ WHERE clause ใน SQL เพื่อกรองข้อมูลตามสถานะที่ถูกเลือก
    $stmt = $conn->prepare("SELECT member_id, member_name, member_sername, password, member_status FROM tbl_member WHERE member_status = :filterStatus");
    $stmt->bindParam(':filterStatus', $filterStatus, PDO::PARAM_STR);
    $stmt->execute();

    $filteredMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // ถ้าไม่มีการกรอง ดึงข้อมูลทั้งหมด
    $stmt = $conn->prepare("SELECT member_id, member_name, member_sername, password, member_status FROM tbl_member");
    $stmt->execute();

    $filteredMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<?php include('testmenu.php'); ?>
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
    <h1>Member list <a href="FormAddMember.php" class="btn btn-info">+Add Member</a> </h1>

    <!-- ส่วนของฟอร์มสำหรับกรองข้อมูล -->
    <form method="post">
        <label for="filter_status">Filter by status:</label>
        <select name="filter_status" id="filter_status">
            
            <option value="admin">Admin</option>
            <option value="member">Member</option>
            <option value="user">User</option>
        </select>
            <button type="submit">Filter</button> 
        <!-- เพิ่มฟอร์มล้างการกรอง -->
            <form method="post">
                <button type="submit" name="clear_filter">Clear Filter</button>
            </form>
    </form>

    <table>
        <thead>
            <tr>
                <th width="7%">ID</th>
                <th width="15%">name</th>
                <th width="15%">sername</th>
                <th width="20%">Username</th>
                <th width="15%">Password</th>
                <th width="10%">Status</th>
                <th width="5%">Edit</th>
                <th width="5%">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredMembers as $member): ?>
                <tr>
                    <td><?= $member['member_id']; ?></td>
                    <td><?= $member['member_name']; ?></td>
                    <td><?= $member['member_sername']; ?></td>
                    <td><?= $member['member_status']; ?></td>
                    <td><?= $member['password'];?></td>
                    <td><?= $member['member_status'];?></td>
                    <td><a href="memberEdit.php?edit=<?= $member['member_id'];?>" class="btn btn-warning btn-sm">แก้ไข</a></td>
                    <td><a href="Delmember.php?id=<?= $member['member_id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>

                    </tr>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>

