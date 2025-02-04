<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新建密码本</title>
    <link rel="stylesheet" type="text/css" href="./static/css/new.css">
    <link rel="icon" type="image/png" href="./static/img/logo.png">
</head>

<body>
    <h2>新建密码本</h2>
    <!-- 返回主页 -->
    <form action="notepad.php" method="post">
        <input type="submit" value="返回主页">
    </form>
    <div class="form-wrapper">
        <form action="save_note.php" method="post">
            <label for="title">标题</label>
            <input type="text" id="title" name="title" required>
            <label for="content">网址</label>
            <textarea id="content" name="content" required></textarea>
            <label for="content2">账号</label>
            <textarea id="content2" name="content2"></textarea>
            <label for="content3">密码</label>
            <textarea id="content3" name="content3"></textarea>
            <button type="submit" class="save-button">保存</button>
        </form>
    </div>
</body>

</html>