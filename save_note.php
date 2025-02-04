<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
// 引入数据库连接文件
require_once 'db_connection.php';

$title = $_POST["title"];
$content = $_POST["content"];
$content2 = $_POST["content2"];
$content3 = $_POST["content3"];

$sql = "INSERT INTO notes (title, content, content2, content3) VALUES ('$title', '$content', '$content2', '$content3')";

if ($conn->query($sql) === TRUE) {
    header("Location: notepad.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>