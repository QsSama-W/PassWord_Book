<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
// 引入数据库连接文件
require_once 'db_connection.php';

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$user_id = $_SESSION["user_id"];
$sql_check = "SELECT id FROM notes WHERE id = $id AND user_id = $user_id";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows == 0) {
    header("Location: notepad.php");
    exit();
}

$sql = "DELETE FROM notes WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: notepad.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
