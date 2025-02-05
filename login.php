<?php
session_start();
// 引入数据库连接文件
require_once 'db_connection.php';

// 获取设备识别码和用户输入的用户名、密码
$input_username = $_POST['username'] ?? null;
$input_password = $_POST['password'] ?? null;
$deviceId = $_POST['deviceId'] ?? null;
// 获取客户端 IP 地址
$client_ip = $_SERVER['REMOTE_ADDR'];

// 定义错误次数和时间限制
$max_attempts = 2;
$lockout_time = 120; // 2 分钟

// 检查设备相关的错误记录
if (isset($_SESSION['login_attempts']['device'][$deviceId])) {
    $device_attempts = $_SESSION['login_attempts']['device'][$deviceId]['attempts'];
    $device_last_attempt_time = $_SESSION['login_attempts']['device'][$deviceId]['last_attempt_time'];
    $device_time_since_last_attempt = time() - $device_last_attempt_time;

    // 检查设备是否在锁定时间内且错误次数达到上限
    if ($device_attempts >= $max_attempts && $device_time_since_last_attempt < $lockout_time) {
        $remaining_time = $lockout_time - $device_time_since_last_attempt;
        echo "由于您输入错误次数超过上限，请在 $remaining_time 秒后重试。";
        exit;
    } elseif ($device_time_since_last_attempt >= $lockout_time) {
        // 设备锁定时间已过，重置错误次数
        $_SESSION['login_attempts']['device'][$deviceId]['attempts'] = 0;
    }
} else {
    // 初始化设备相关的错误记录
    $_SESSION['login_attempts']['device'][$deviceId] = [
        'attempts' => 0,
        'last_attempt_time' => 0
    ];
}

// 检查 IP 相关的错误记录
if (isset($_SESSION['login_attempts']['ip'][$client_ip])) {
    $ip_attempts = $_SESSION['login_attempts']['ip'][$client_ip]['attempts'];
    $ip_last_attempt_time = $_SESSION['login_attempts']['ip'][$client_ip]['last_attempt_time'];
    $ip_time_since_last_attempt = time() - $ip_last_attempt_time;

    // 检查 IP 是否在锁定时间内且错误次数达到上限
    if ($ip_attempts >= $max_attempts && $ip_time_since_last_attempt < $lockout_time) {
        $remaining_time = $lockout_time - $ip_time_since_last_attempt;
        echo "由于您输入错误次数超过上限，请在 $remaining_time 秒后重试。";
        exit;
    } elseif ($ip_time_since_last_attempt >= $lockout_time) {
        // IP 锁定时间已过，重置错误次数
        $_SESSION['login_attempts']['ip'][$client_ip]['attempts'] = 0;
    }
} else {
    // 初始化 IP 相关的错误记录
    $_SESSION['login_attempts']['ip'][$client_ip] = [
        'attempts' => 0,
        'last_attempt_time' => 0
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 使用预处理语句
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username =?");
    if (!$stmt) {
        // 预处理语句准备失败
        echo "数据库查询错误，请稍后重试。";
        $conn->close();
        exit;
    }
    // 绑定参数，"s" 表示参数是字符串类型
    $stmt->bind_param("s", $input_username);
    // 执行查询
    if (!$stmt->execute()) {
        // 执行查询失败
        echo "数据库查询错误，请稍后重试。";
        $stmt->close();
        $conn->close();
        exit;
    }
    // 获取查询结果
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($input_password, $row["password"])) {
            // 登录成功，重置设备和 IP 的错误次数
            $_SESSION['login_attempts']['device'][$deviceId]['attempts'] = 0;
            $_SESSION['login_attempts']['ip'][$client_ip]['attempts'] = 0;
            $_SESSION["user_id"] = $row["id"];
            // 保存用户名到会话
            $_SESSION["username"] = $input_username;
            echo 'success';
        } else {
            // 密码错误，增加设备和 IP 的错误次数
            $_SESSION['login_attempts']['device'][$deviceId]['attempts']++;
            $_SESSION['login_attempts']['device'][$deviceId]['last_attempt_time'] = time();
            $_SESSION['login_attempts']['ip'][$client_ip]['attempts']++;
            $_SESSION['login_attempts']['ip'][$client_ip]['last_attempt_time'] = time();
            echo "用户名或者密码错误";
        }
    } else {
        // 用户名不存在，增加设备和 IP 的错误次数
        $_SESSION['login_attempts']['device'][$deviceId]['attempts']++;
        $_SESSION['login_attempts']['device'][$deviceId]['last_attempt_time'] = time();
        $_SESSION['login_attempts']['ip'][$client_ip]['attempts']++;
        $_SESSION['login_attempts']['ip'][$client_ip]['last_attempt_time'] = time();
        echo "用户名或者密码错误";
    }
    // 关闭预处理语句
    $stmt->close();
}

$conn->close();
?>
