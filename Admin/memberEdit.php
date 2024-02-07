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

// Check if the member_id is set in the URL
if(isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Retrieve existing data from the database
    $stmt = $conn->prepare("SELECT * FROM tbl_member WHERE member_id = :member_id");
    $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the data
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if member exists
    if(!$member) {
        echo "Member not found.";
        exit();
    } else {
    echo "Invalid request. Member ID not provided.";
    exit();
}
}
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6"> <br />
      <h4 align="center"> Edit Member </h4>
      
      <form  name="editproduct" action="memberEdit._DB.php" method="POST" enctype="multipart/form-data"  class="form-horizontal">
        <div class="form-group">
          <div class="col-sm-8">
            <p> ID </p>
            <input type="text"  name="member_id" class="form-control" value="<?php echo $member['member_id']; ?>" ID />
          </div>
        </div>

        <div class="form-group">
        <div class="col-sm-8">
            <p> Name</p>
            <input type="text"  name="member_name" class="form-control" value="<?php echo $member['member_name']; ?>" name/>
          </div>
        </div>

        <div class="form-group">
        <div class="col-sm-8">
            <p> Sername </p>
            <input type="text"  name="member_sername" class="form-control" value="<?php echo $member['member_sername']; ?>" sername />
          </div>
        </div>

        <div class="form-group">
        <div class="col-sm-8">
            <p> username </p>
            <input type="text"  name="username" class="form-control" value="<?php echo $member['username']; ?>" Username />
          </div>
        </div>


        <div class="form-group">
    <div class="col-sm-8">
        <p> Password </p>
        <input type="password" name="password" id="password" class="form-control" value="<?php echo $member['password']; ?>" password />
        <input type = "checkbox"<span id="password-toggle" onclick="togglePasswordVisibility('password')">Show Password</span>>
    </div>
</div>

        <div class="form-group">
        <div class="col-sm-5">
          <p>Choose User Type:</p>
            <select name="member_status" class="form-control" required>
            <option value="admin" <?php echo ($member['member_status'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?php echo ($member['member_status'] == 'user') ? 'selected' : ''; ?>>User</option>
            <option value="member" <?php echo ($member['member_status'] == 'member') ? 'selected' : ''; ?>>Member</option>
          </select>
        </div>
        <br>
        <br>
        <div class="form-group">
          <div class="col-sm-12">
          <button type="submit" name="btn_update">Update Member</button>
    </form>

</body>
</html>
<style>
  .col-sm-12 {
    width: 100%;
    padding-top: 50px;
    padding-left: 250px;
}
row{
  margin-left: 50px;
}
#password-toggle {
            margin-left: 10px;
            cursor: pointer;
        }
</style>

<script>
        
    function togglePasswordVisibility(fieldId) {
        var passwordField = document.getElementById(fieldId);
        var passwordToggle = document.getElementById(fieldId + '-toggle');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordToggle.textContent = 'Hide Password';
        } else {
            passwordField.type = 'password';
            passwordToggle.textContent = 'Show Password';
        }
    }
    function confirmUpdate() {
            return confirm("คุณต้องการที่จะแก้ไขข้อมูลใช่ไหม?");
        }
        function validateForm() {
        // เพิ่มตรวจสอบว่าข้อมูลถูกกรอกครบทุกช่องหรือไม่
        var id = document.getElementsByName("member_id")[0].value;
        var name = document.getElementsByName("member_name")[0].value;
        var sername = document.getElementsByName("member_sername")[0].value;
        var username = document.getElementsByName("username")[0].value;
        var password = document.getElementById("password").value;
        var status = document.getElementById("member_status").value;
        // เพิ่มตรวจสอบข้อมูลจากช่องอื่น ๆ ตามต้องการ

        // ตรวจสอบว่าข้อมูลถูกกรอกครบทุกช่องหรือไม่
        if (id === '' ||name === '' || sername === '' || username === '' || password === '' ||status === '') {
            return false;
        }

        // ถ้าผ่านการตรวจสอบทุกอย่าง
        return true;
    }
    </script>
