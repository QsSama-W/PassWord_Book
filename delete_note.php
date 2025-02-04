<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
// 引入数据库连接文件
require_once 'db_connection.php';

$id = $_GET["id"];
$sql = "DELETE FROM notes WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: notepad.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>