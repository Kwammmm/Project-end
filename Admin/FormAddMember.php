
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
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1">
<title>devbanban.com</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6"> <br />
      <h4 align="center"> Add Member </h4>
      <hr />
      
      <form  name="addproduct" action="FormAddMember_DB.php" method="POST" enctype="multipart/form-data"  class="form-horizontal">
        <div class="form-group">
        <div class="col-sm-8">
            <p> Name</p>
            <input type="text"  name="member_name" class="form-control" required placeholder="name" />
          </div>
        </div>

        <div class="form-group">
        <div class="col-sm-8">
            <p> Sername </p>
            <input type="text"  name="member_sername" class="form-control" required placeholder="sername" />
          </div>
        </div>

        <div class="form-group">
        <div class="col-sm-8">
            <p> username </p>
            <input type="text"  name="username" class="form-control" required placeholder="Username" />
          </div>
        </div>


        <div class="form-group">
    <div class="col-sm-8">
        <p> Password </p>
        <input type="password" name="password" id="password" class="form-control" required placeholder="password" />
        <span id="password-toggle" onclick="togglePasswordVisibility('password')">Show Password</span>
    </div>
</div>

        <div class="form-group">
        <div class="col-sm-5">
          <p>Choose User Type:</p>
            <select name="member_status" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="member">Member</option>
          </select>
        </div>
        <br>
        <br>


        <div class="form-group">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary" name="btnadd">เพิ่มข้อมูล</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

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
    function validateAndConfirm() {
        // เรียกใช้ฟังก์ชันเพื่อตรวจสอบว่าข้อมูลถูกกรอกครบทุกช่องหรือไม่
        var isValid = validateForm();

        if (isValid) {
            // ใช้ window.confirm เพื่อแสดง dialog และรับผลลัพธ์
            var result = window.confirm("ต้องการเพิ่มข้อมูลหรือไม่?");

            // ถ้าผู้ใช้กด "ตกลง" (OK) คืนค่า true
            // ถ้าผู้ใช้กด "ยกเลิก" (Cancel) คืนค่า false
            return result;
        } else {
            // ถ้าข้อมูลไม่ถูกกรอกครบทุกช่อง แจ้งเตือนผู้ใช้
            alert("กรุณากรอกข้อมูลให้ครบทุกช่อง");
            return false;
        }
    }

    function validateForm() {
        // เพิ่มตรวจสอบว่าข้อมูลถูกกรอกครบทุกช่องหรือไม่
        var id = document.getElementsByName("member_id").value;
        var name = document.getElementsByName("member_name").value;
        var sername = document.getElementsByName("member_sername").value;
        var username = document.getElementsByName("username").value;
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







