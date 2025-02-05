-- 创建数据库
CREATE DATABASE IF NOT EXISTS notepad_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE notepad_db;

-- 创建用户表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- 插入默认用户
INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$PQaM8NtAyPCpY.Oc/dBVlusZl6.lb2fjQec9pRCHOyhOM2cbLArOe'); 

-- 创建记录表
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    content2 TEXT,
    content3 TEXT,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

DELIMITER //

CREATE FUNCTION `firstPinyin`(P_NAME VARCHAR(255)) RETURNS varchar(255) CHARSET utf8mb4
DETERMINISTIC
BEGIN
    DECLARE V_RETURN VARCHAR(255);
    DECLARE V_FIRST_CHAR VARCHAR(1);
    SET V_FIRST_CHAR = UPPER(LEFT(CONVERT(P_NAME USING gbk), 1));
    IF V_FIRST_CHAR REGEXP '[A-Z]' THEN
        SET V_RETURN = V_FIRST_CHAR;
    ELSE
        SET V_RETURN = ELT(INTERVAL(CONV(HEX(LEFT(CONVERT(P_NAME USING gbk), 1)), 16, 10),
            0xB0A1, 0xB0C5, 0xB2C1, 0xB4EE, 0xB6EA, 0xB7A2, 0xB8C1, 0xB9FE, 0xBBF7,
            0xBFA6, 0xC0AC, 0xC2E8, 0xC4C3, 0xC5B6, 0xC5BE, 0xC6DA, 0xC8BB,
            0xC8F6, 0xCBFA, 0xCDDA, 0xCEF4, 0xD1B9, 0xD4D1),
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'W', 'X', 'Y', 'Z');
    END IF;
    RETURN V_RETURN;
END //

DELIMITER ;
