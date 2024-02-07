<?php
// Include database connection
require_once 'connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $member_id = $_POST['member_id'];
    $member_name = $_POST['member_name'];
    $member_sername = $_POST['member_sername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $member_status = $_POST['member_status'];

    // Update data in the database
    $stmt = $null->prepare("UPDATE tbl_member SET member_name = :member_name, member_sername = :member_sername, username = :username, password = :password, member_status = :member_status WHERE member_id = :member_id");
    $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
    $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
    $stmt->bindParam(':member_sername', $member_sername, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR); // Use hashed password
    $stmt->bindParam(':member_status', $member_status, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Data updated successfully.";
        header("Location: member.php"); // Redirect to member.php
        exit();
    } else {
        echo "Error updating data.";
    }
} else {
    echo "Invalid request.";
}
?>

