document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const timeout = urlParams.get('timeout');
    if (timeout === 'true') {
        const timeoutMessage = document.createElement('p');
        timeoutMessage.style.color = 'red';
        timeoutMessage.textContent = '长时间无动作已退出登录';
        const form = document.querySelector('form');
        form.parentNode.insertBefore(timeoutMessage, form);
    }
});
