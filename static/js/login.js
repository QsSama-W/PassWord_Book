// 生成或获取设备识别码
function getDeviceId() {
    let deviceId = localStorage.getItem('deviceId');
    if (!deviceId) {
        deviceId = Math.random().toString(36).substr(2, 9);
        localStorage.setItem('deviceId', deviceId);
    }
    return deviceId;
}

function validateLogin() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const deviceId = getDeviceId();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'login.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = this.responseText;
            if (response === 'success') {
                window.location.href = 'notepad.php';
            } else {
                showPopup(response);
                if (response.includes('由于您输入错误超上限，请在')) {
                    const remainingTime = parseInt(response.match(/\d+/)[0]);
                    startCountdown(remainingTime);
                }
            }
        }
    };
    xhr.send(`username=${username}&password=${password}&deviceId=${deviceId}`);
    return false;
}

function showPopup(message) {
    const popup = document.getElementById('popup');
    const popupOverlay = document.getElementById('popup-overlay');
    const popupMessage = document.getElementById('popup-message');

    popupMessage.textContent = message;
    popup.style.display = 'block';
    popupOverlay.style.display = 'block';
}

function closePopup() {
    const popup = document.getElementById('popup');
    const popupOverlay = document.getElementById('popup-overlay');

    popup.style.display = 'none';
    popupOverlay.style.display = 'none';
}

function startCountdown(remainingTime) {
    const popupMessage = document.getElementById('popup-message');
    const intervalId = setInterval(() => {
        if (remainingTime > 0) {
            remainingTime--;
            popupMessage.textContent = `由于您输入错误超上限，请在 ${remainingTime} 秒后重试。`;
        } else {
            clearInterval(intervalId);
            popupMessage.textContent = '锁定时间已过，您可以再次尝试登录。';
        }
    }, 1000);
}

// 超时退出提示框
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
