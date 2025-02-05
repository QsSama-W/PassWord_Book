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
        /* 引入错误弹窗样式 */
       .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    
    <form id="registerForm" action="register_process.php" method="post">
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
    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup" id="popup">
        <span class="popup-close" id="popupClose">&times;</span>
        <p id="popupMessage"></p>
    </div>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // 阻止表单默认提交行为

            const formData = new FormData(this);

            fetch('register_process.php', {
                method: 'POST',
                body: formData
            })
           .then(response => response.text())
           .then(data => {
                // 显示弹窗
                document.getElementById('popupMessage').textContent = data;
                document.getElementById('popupOverlay').style.display = 'block';
                document.getElementById('popup').style.display = 'block';
            })
           .catch(error => {
                console.error('Error:', error);
            });
        });

        document.getElementById('popupClose').addEventListener('click', function() {
            // 关闭弹窗
            document.getElementById('popupOverlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        });
    </script>
    <script src="./static/js/notepad.js"></script>
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
