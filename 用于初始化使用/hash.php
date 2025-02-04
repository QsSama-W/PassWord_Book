<?php
$plainPassword = 'admin'; // 通过http://你的域名/hash.php请求获取哈希值填入sql进行初始化
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>