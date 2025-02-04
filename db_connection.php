<?php
// 数据库配置信息
$servername = "localhost";  // 数据库主机名
$username = "your_username";  // 数据库用户名
$password = "your_password";  // 数据库密码
$dbname = "your_database_name";    // 数据库名

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>
