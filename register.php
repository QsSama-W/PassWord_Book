<?php
session_start();

// 生成或验证 CSRF 令牌
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 检查用户是否已登录
if (isset($_SESSION["user_id"])) {
    require_once 'db_connection.php';
    
    $stmt_config = $conn->prepare("SELECT value FROM config WHERE key_name = 'allowed_roles'");
    $stmt_config->execute();
    $result_config = $stmt_config->get_result();
    $row_config = $result_config->fetch_assoc();
    $allowed_roles = explode(',', $row_config['value']);

    $user_id = $_SESSION["user_id"];
    $stmt_user = $conn->prepare("SELECT role FROM users WHERE id =?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows == 1) {
        $row_user = $result_user->fetch_assoc();
        $user_role = $row_user['role'];

        if (in_array($user_role, $allowed_roles)) {
            ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
</head>

<body>
    <h2>用户注册</h2>
    <form action="register_process.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">密码:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="注册">
    </form>
</body>

</html>
<?php
        } else {
            echo "请使用管理员账号登录";
        }
    } else {
        // 用户信息不存在，重定向到登录页面
        header("Location: login.html");
        exit();
    }

    $stmt_config->close();
    $stmt_user->close();
    $conn->close();
} else {
    // 如果用户未登录，重定向到登录页面
    header("Location: login.html");
    exit();
}
?>