// 设置倒计时时间（单位：秒）
const idleTime = 300; // 5 分钟
let timer;

// 用户有操作时重置定时器
function resetTimer() {
    clearTimeout(timer);
    timer = setTimeout(logout, idleTime * 1000);
}

// 退出操作
function logout() {
    window.location.href = 'logout.php';
}

// 监听用户操作事件
document.addEventListener('mousemove', resetTimer);
document.addEventListener('keydown', resetTimer);
document.addEventListener('scroll', resetTimer);

// 初始化定时器
resetTimer();
