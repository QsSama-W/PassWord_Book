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
    <form action="logout2.php" method="post">
        <?php
        session_start();
        if (isset($_SESSION["username"])) {
            $username = $_SESSION["username"];
            echo "<input type='submit' value='{$username} 登出'>";
        } else {
            echo "<input type='submit' value='登出'>";
        }
        ?>
    </form>
    <button class="new-note-button" onclick="window.location.href='new_note.php'">新建</button>

    <!-- 搜索框，搜索按钮，重置搜索按钮 -->
    <form id="searchForm" action="" method="get">
        <div class="search-container">
            <select id="searchType" name="searchType">
                <option value="title">标题</option>
                <option value="content">网址</option>
                <option value="content2">账号</option>
                <option value="content3">密码</option>
            </select>
            <input type="text" id="searchInput" name="search" placeholder="请输入搜索内容">
        </div>
        <div class="search-container">
            <button type="submit" id="searchButton">搜索</button>
            <button type="button" id="resetButton" onclick="resetSearch()">重置搜索</button>
        </div>
    </form>

    <div class="notes-wrapper">
        <?php
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.html");
            exit();
        }
        
        require_once 'db_connection.php';
        
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : 'title';
        $whereClause = '';
        if ($search) {
            $search = $conn->real_escape_string($search);
            $whereClause = " WHERE user_id = {$_SESSION["user_id"]} AND $searchType LIKE '%$search%'";
        } else {
            $whereClause = " WHERE user_id = {$_SESSION["user_id"]}";
        }

        // 修改 SQL 查询语句以实现按标题首字符排序
        $sql = "SELECT id, title, content, content2, content3, firstPinyin(title) as first_pinyin 
                FROM notes 
                $whereClause
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
                echo "<a href='delete_note.php?id=" . $row["id"] . "' onclick=\"return confirm('确定要删除这条记录吗？');\">删除</a>";
                echo '</div>';
            }
        } else {
            echo "<p>暂无符合条件的记录</p>";
        }

        $conn->close();
        ?>
    </div>
    <script>
        function resetSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchType').value = 'title';
            document.getElementById('searchForm').submit();
        }
    </script>
    <script src="./static/js/notepad.js"></script>
</body>

</html>
