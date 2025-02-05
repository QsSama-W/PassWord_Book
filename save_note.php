<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
require_once 'db_connection.php';

$title = $_POST["title"];
$content = $_POST["content"];
$content2 = $_POST["content2"];
$content3 = $_POST["content3"];
$user_id = $_SESSION["user_id"];

$sql = "INSERT INTO notes (title, content, content2, content3, user_id) VALUES (?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $title, $content, $content2, $content3, $user_id);
if ($stmt->execute()) {
    header("Location: notepad.php");
    exit();
} else {
    echo "保存失败：" . $conn->error;
}
$stmt->close();
$conn->close();
?>
