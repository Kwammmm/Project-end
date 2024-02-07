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
<?php include('menu_user.php'); ?>



<!DOCTYPE html>
<html lang="en">
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
    <h1>Report</h1>
    <!-- นำฟอร์มค้นหาไว้ที่ด้านบนของตาราง -->
    </div>
</div>
<div style="width: 1217px; height: auto; background: #FFFFFF; border: 1px solid black; border-radius: 4px;margin-left: 19%;">
    <form method="post">
        <label for="search">Search: </label>
        <input type="text" name="search" id="search" placeholder="ป้อนชื่อหรือนามสกุล">
        <button type="submit">search</button>
<?php
require_once 'connect.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if(isset($_SESSION['member_name'])){
    // ถ้ามีการล็อกอินแล้ว
    $member_name = $_SESSION['member_name'];

    // ใช้ JOIN เพื่อรวมข้อมูลจาก tbl_member และ report
    $stmt = $conn->prepare("SELECT report.*, tbl_member.member_name AS borrower_name FROM report
                            LEFT JOIN tbl_member ON report.borrower_id = tbl_member.member_id
                            WHERE tbl_member.member_name = :member_name");
    $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
    $stmt->execute();
    $filteredReport = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($filteredReport) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th width='7%'>Report ID</th>";
        echo "<th width='15%'>Borrower Name</th>";
        echo "<th width='15%'>Borrower Due Date</th>";
        echo "<th width='20%'>Return Date</th>";
        echo "<th width='15%'>Item ID</th>";
        echo "<th width='10%'>Item Name</th>";
        echo "<th width='10%'>Serial Number</th>";
        echo "<th width='10%'>Borrow Quantity</th>";
        echo "<th width='10%'>Return Quantity</th>";
        echo "<th width='10%'>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($filteredReport as $report) {
            echo "<tr>";
            echo "<td>{$report['report_id']}</td>";
            echo "<td>{$report['borrower_name']}</td>";
            echo "<td>{$report['borrow_due_date']}</td>";
            echo "<td>{$report['return_date']}</td>";
            echo "<td>{$report['items_id']}</td>";
            echo "<td>{$report['items_name']}</td>";
            echo "<td>{$report['serial_number']}</td>";
            echo "<td>{$report['borrow_quantity']}</td>";
            echo "<td>{$report['return_quantity']}</td>";
            echo "<td>{$report['status']}</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "ไม่พบข้อมูลใน Report";
    }
} else {
    // ถ้ายังไม่ได้ล็อกอิน
    echo "กรุณาเข้าสู่ระบบ";
}
?>
</form>
    </div>
</body>
</html>
