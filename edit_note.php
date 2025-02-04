<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑密码本</title>
    <link rel="stylesheet" type="text/css" href="./static/css/edit.css">
    <link rel="icon" type="image/png" href="./static/img/logo.png">
</head>

<body>
    <!-- 编辑密码本标题 -->
    <h1>编辑密码本</h1>
    <!-- 返回主页按钮 -->
    <form action="notepad.php" method="post">
        <input type="submit" value="返回主页">
    </form>
    <div class="container">
        <?php
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.html");
            exit();
        }
        require_once 'db_connection.php';

        $id = $_GET["id"];
        $sql = "SELECT id, title, content, content2, content3 FROM notes WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            echo "<form action='update_note.php' method='post'>";
            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            echo "<label for='title'>标题</label>";
            echo "<input type='text' id='title' name='title' value='" . $row["title"] . "' required>";
            echo "<label for='content'>网址</label>";
            echo "<textarea id='content' name='content' required>" . $row["content"] . "</textarea>";
            echo "<label for='content2'>账号</label>";
            echo "<textarea id='content2' name='content2'>" . $row["content2"] . "</textarea>";
            echo "<label for='content3'>密码</label>";
            echo "<textarea id='content3' name='content3'>" . $row["content3"] . "</textarea>";
            // 将保存修改改为按钮
            echo "<button type='submit' class='save-button'>保存修改</button>";
            echo "</form>";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>