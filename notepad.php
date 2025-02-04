<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>密码本</title>
    <link rel="stylesheet" type="text/css" href="./static/css/styles.css">
    <link rel="icon" type="image/png" href="./static/img/logo.png">
</head>

<body>
    <h2>密码本</h2>
    <!-- 登出按钮 -->
    <form action="logout.php" method="post">
        <input type="submit" value="登出">
    </form>
    <button class="new-note-button" onclick="window.location.href='new_note.php'">新建</button>
    <div class="notes-wrapper">
        <?php
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.html");
            exit();
        }
        require_once 'db_connection.php';

        // 修改 SQL 查询语句以实现按标题首字符排序
        $sql = "SELECT id, title, content, content2, content3, firstPinyin(title) as first_pinyin 
                FROM notes 
                ORDER BY 
                    CASE 
                        WHEN LEFT(title, 1) REGEXP '^[^a-zA-Z0-9]' AND HEX(LEFT(CONVERT(title USING binary), 1)) NOT BETWEEN 'E4' AND 'F0' THEN 1
                        WHEN LEFT(title, 1) REGEXP '^[0-9]' THEN 2
                        WHEN LEFT(title, 1) REGEXP '^[a-zA-Z]' THEN 3
                        WHEN LEFT(title, 1) REGEXP '^[^a-zA-Z0-9]' AND HEX(LEFT(CONVERT(title USING binary), 1)) BETWEEN 'E4' AND 'F0' THEN 4
                        ELSE 5
                    END, 
                    CASE 
                        WHEN LEFT(title, 1) REGEXP '^[0-9]' THEN CAST(LEFT(title, 1) AS UNSIGNED)
                        WHEN LEFT(title, 1) REGEXP '^[a-zA-Z]' THEN UPPER(LEFT(title, 1))
                        WHEN LEFT(title, 1) REGEXP '^[^a-zA-Z0-9]' AND HEX(LEFT(CONVERT(title USING binary), 1)) BETWEEN 'E4' AND 'F0' THEN first_pinyin
                        ELSE LEFT(title, 1)
                    END,
                    title";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="note">';
                echo "<h3>" . $row["title"] . "</h3>";
                echo "<p>网址：" . $row["content"] . "</p>";
                echo "<p>账号：" . $row["content2"] . "</p>";
                echo "<p>密码：" . $row["content3"] . "</p>";
                echo "<a href='edit_note.php?id=" . $row["id"] . "'>修改</a>";
                echo "<a href='delete_note.php?id=" . $row["id"] . "'>删除</a>";
                echo '</div>';
            }
        } else {
            echo "<p>暂无记录</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
<script src="./static/js/notepad.js"></script>
</html>
