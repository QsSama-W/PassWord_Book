# PassWord_Book
这是一个借助 AI 开发的极简网页密码本，不具备加密功能。请谨慎使用，避免敏感信息泄露。

**功能介绍：**

☑多次登录错误等待(在`login.php`中配置错误次数以及等待时间)

☑超时自动退出(在`./notepad.js`中配置超时时长)

☑特殊字符，0-9，Aa-Zz，中文字符自动排序

# 使用说明

**1、配置数据库参数：** 
在 `db_connection.php` 文件中配置你的数据库相关参数。
```
$servername = "localhost";  // 数据库主机名
$username = "your_username";  // 数据库用户名
$password = "your_password";  // 数据库密码
$dbname = "your_database_name";  // 数据库名
```
**2、初始化数据库 - 设置登录密码：**
打开 `./hash.php` 文件，将文件内容替换为你设定的登录密码。运行该文件，获取加密后的密码，然后将其填入 SQL 中，完成数据库的初始化操作。
重要提示：完成初始化后，请务必删除 `hash.php` 文件，以增强安全性。

```
<?php
$plainPassword = 'admin'; // 修改admin为你的密码，通过http://www.你的域名.com/hash.php请求获取哈希值，填入sql脚本中
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>
```

**3、初始化数据库 - 执行 SQL 脚本：**
使用文本编辑器打开 `./sql.sql` 文件，复制全部内容，然后粘贴到数据库管理工具中执行 SQL 脚本，完成数据库的结构搭建和初始数据填充。
安全建议：初始化完成后，及时删除 `sql.sql` 文件，防止脚本内容泄露带来的风险。
```
-- 插入默认用户
INSERT INTO users (username, password, role) // 分别是:用户名，密码，权限
VALUES ('admin', '$2y$10$PQaM8NtAyPCpY.Oc/dBVlusZl6.lb2fjQec9pRCHOyhOM2cbLArOe','admin');  // 修改那串乱码为你自己密码的哈希值
```
**4、启动应用：**
将除了用于初始化的上述文件之外的其他所有文件，移动到域名对应的文件夹下，然后在浏览器中访问 `http://www.你的域名.com/login.html`，登录你设置好的用户名和密码，即可使用。

![1.png](https://github.com/QsSama-W/PassWord_Book/blob/main/1.png)

**5、新增用户页面**
打开`./register.php`文件，进行注册新用户，默认权限为`user`，第一个用户为`admin`权限

**6、**

> [!WARNING]
>**注意事项：**
> 
> **由于本密码本无加密功能，切勿用于存储重要或敏感的密码信息。
> 在使用过程中，如遇到任何问题或错误，请仔细检查各步骤的配置是否正确，或咨询AI寻求解决方案。**

# PS:

**本人无法提供任何技术支持，有疑问请带着[本项目代码](https://github.com/QsSama-W/PassWord_Book)咨询AI**
