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
const searchTypeSelect = document.getElementById('searchType'); // 获取下拉框元素

// 保留搜索框中的文本
searchButton.addEventListener('click', function () {
    const searchValue = searchInput.value;
    const searchTypeValue = searchTypeSelect.value; // 获取下拉框选中的值
    const currentUrl = new URL(window.location.href);

    if (searchValue) {
        currentUrl.searchParams.set('search', searchValue);
    } else {
        currentUrl.searchParams.delete('search');
    }

    // 设置搜索类型参数
    currentUrl.searchParams.set('searchType', searchTypeValue);

    // 页面跳转后恢复搜索框内容
    window.history.replaceState({}, '', currentUrl.toString());
    // 重新加载页面
    location.reload();
});

// 重置搜索按钮点击事件
resetButton.addEventListener('click', function () {
    searchInput.value = ''; // 清空搜索框内容
    searchTypeSelect.value = 'title'; // 重置下拉框为默认值
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('search'); // 删除 URL 中的搜索参数
    currentUrl.searchParams.set('searchType', 'title'); // 设置搜索类型为默认值
    window.location.href = currentUrl.toString(); // 跳转页面
});

// 页面加载时恢复搜索框内容和下拉框选中状态
window.addEventListener('load', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const searchValue = urlParams.get('search');
    const searchTypeValue = urlParams.get('searchType');

    if (searchValue) {
        searchInput.value = searchValue;
    }

    if (searchTypeValue) {
        searchTypeSelect.value = searchTypeValue; // 恢复下拉框选中状态
    }
});
