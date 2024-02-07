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
if(isset($_POST['filter_category'])){
    $filterstatusmember = $_POST['filter_category'];

    // ถ้าเลือก "ทั้งหมด" ให้ดึงข้อมูลทั้งหมด
    if($filterstatusmember === "ทั้งหมด"){
        $stmt = $conn->prepare("SELECT status_name FROM tbl_statusmember");
    } else {
        // ถ้าไม่ใช่ "ทั้งหมด" ให้ใช้ WHERE clause ใน SQL เพื่อกรองข้อมูลตามหมวดหมู่ที่ถูกเลือก
        $stmt = $conn->prepare("SELECT status_name FROM tbl_statusmember WHERE status_name = :filterstatusmember");
        $stmt->bindParam(':filterstatusmember', $filterstatusmember, PDO::PARAM_STR);
    }

    

    $filterstatusmember = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // ถ้าไม่มีการกรอง ดึงข้อมูลทั้งหมด
    $stmt = $conn->prepare("SELECT status_name FROM tbl_statusmember");
    $stmt->execute();

    $filterstatusmember = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$stmtCategories = $conn->prepare("SELECT DISTINCT status_name FROM tbl_statusmember");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_COLUMN);
?>


<?php include('testmenu.php'); ?>
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

    <h1>Status Member<a href="Addstatus.php" class="btn btn-info">+Add Member</a></h1>

    <!-- ส่วนของฟอร์มสำหรับกรองข้อมูล -->
    <form method="post">
        <label for="filter_statusmember">Filter by statusmember:</label>
        <select name="filter_statusmember" id="filter_statusmember">
            <?php
            require_once 'connect.php';

            // Query to get distinct categories
            $stmt = $conn->prepare("SELECT DISTINCT status_name FROM tbl_statusmember");
            $stmt->execute();

            // Loop through the result and create options
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $statusmember = $row['status_name'];
                echo "<option value='$statusmember'>$statusmember</option>";
            }
            ?>
        </select>

        <button type="submit">Filter</button>
        <!-- เพิ่มฟอร์มล้างการกรอง -->
        <form method="post">
                <button type="submit" name="clear_filter">Clear Filter</button>
            </form>

            <?php
    // Handle filter logic here
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $filter_statusmember = isset($_POST['filter_statusmember']) ? $_POST['filter_statusmember'] : '';
        
        // Display filtered data
        $sql = "SELECT * FROM tbl_statusmember";
        if (!empty($filter_statusmember)) {
            $sql .= " WHERE status_name = :filter_statusmember";
        }

        $stmt = $conn->prepare($sql);
        if (!empty($filter_statusmember)) {
            $stmt->bindParam(':filter_statusmember', $filter_statusmember, PDO::PARAM_STR);
        }

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['status_name']}<br>";
        }
    }

    // Close the connection
    $conn = null;
    ?>


    </form>

    <table>
        <thead>
            <tr>
                <th width="15%">name</th>
                <th width="5%">Edit</th>
                <th width="5%">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filterstatusmember as $statusmember): ?>
                <tr>
                    <td><?= $statusmember['status_name']; ?></td>
                    <td><a href="Addcategory.php?id=<?= $k['status_name'];?>" class="btn btn-warning btn-sm">แก้ไข</a></td>         
                    <td><a href="delete_category.php?status_name=<?php echo $row['status_name']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">ลบ</a></td>

                    

                    
                    </tr>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    

</body>
</html>

