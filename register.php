<?php
session_start();

// 生成或验证 CSRF 令牌
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 检查用户是否已登录
if (isset($_SESSION["user_id"])) {
    require_once '#db_connection.php';
    
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
    <link rel="icon" type="image/png" href="./static/img/logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    
    <form action="register_process.php" method="post">
        <h2>用户注册</h2>
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
