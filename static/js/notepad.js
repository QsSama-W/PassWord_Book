// 设置倒计时时间（单位：秒）
const idleTime = 120; // 2 分钟
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

const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const resetButton = document.getElementById('resetButton');

// 保留搜索框中的文本
searchButton.addEventListener('click', function () {
    const searchValue = searchInput.value;
    const currentUrl = new URL(window.location.href);

    if (searchValue) {
        currentUrl.searchParams.set('search', searchValue);
    } else {
        currentUrl.searchParams.delete('search');
    }

    // 页面跳转后恢复搜索框内容
    window.history.replaceState({}, '', currentUrl.toString());
    // 重新加载页面
    location.reload();
});

// 重置搜索按钮点击事件
resetButton.addEventListener('click', function () {
    searchInput.value = ''; // 清空搜索框内容
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('search'); // 删除 URL 中的搜索参数
    window.location.href = currentUrl.toString(); // 跳转页面
});

// 页面加载时恢复搜索框内容
window.addEventListener('load', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const searchValue = urlParams.get('search');

    if (searchValue) {
        searchInput.value = searchValue;
    }
});
