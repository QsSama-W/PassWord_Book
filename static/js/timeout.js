document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const timeout = urlParams.get('timeout');
    if (timeout === 'true') {
        const popupMessage = document.getElementById('popup-message');
        popupMessage.textContent = '长时间无动作已退出登录';
        // 调用showPopup函数显示弹窗
        showPopup('长时间无动作已退出登录');
    }
});
