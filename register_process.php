<?php
session_start();

if (!isset($_POST['csrf_token']) || $_POST['csrf_token']!== $_SESSION['csrf_token']) {
    echo "CSRF 验证失败，请重试。";
    exit();
}

// 检查用户是否已登录
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_id"] == 1) {
        require_once 'db_connection.php';

        // 获取表单数据
        $username = $_POST["username"];
        $password = $_POST["password"];

        // 对密码进行哈希处理
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 检查用户名是否已存在
        $checkSql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 用户名已存在，显示错误信息
            echo "用户名已存在，请选择其他用户名。";
        } else {
            // 插入新用户数据
            $insertSql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                // 注册成功，重定向到登录页面
                header("Location: login.html");
                exit();
            } else {
                // 注册失败，显示错误信息
                echo "注册失败，请稍后重试。";
            }
        }

        // 关闭预处理语句和数据库连接
        $stmt->close();
        $conn->close();
    } else {
        // 如果用户不是管理员账号，显示错误信息
        echo "请使用管理员账号访问。";
    }
} else {
    // 如果用户未登录，重定向到登录页面
    header("Location: login.html");
    exit();
}
?>