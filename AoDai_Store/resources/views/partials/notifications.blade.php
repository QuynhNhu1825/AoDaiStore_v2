<style>
    .notification-stack {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1050;
        display: grid;
        gap: .5rem;
        width: min(24rem, calc(100% - 2rem));
        max-height: calc(100dvh - 2rem);
        overflow: auto;
        pointer-events: none;
    }

    .notification-message {
        position: relative;
        padding: .75rem 2.75rem .75rem 1rem;
        border-radius: .75rem;
        border-left: 4px solid #78716c;
        background: #fff;
        color: #292524;
        box-shadow: 0 4px 16px rgba(28, 25, 23, .16);
        font-size: .875rem;
        overflow-wrap: anywhere;
        pointer-events: auto;
    }

    .notification-message strong {
        display: block;
    }

    .notification-text {
        white-space: pre-line;
    }

    .notification-close {
        position: absolute;
        top: .5rem;
        right: .5rem;
        width: 1.75rem;
        height: 1.75rem;
        border: 0;
        border-radius: .375rem;
        background: transparent;
        color: #57534e;
        font: inherit;
        font-size: 1.25rem;
        cursor: pointer;
    }

    .notification-close:hover,
    .notification-close:focus-visible { background: #f5f5f4; }

    .notification-message[data-icon="success"] { border-color: #15803d; }
    .notification-message[data-icon="warning"] { border-color: #b45309; }
    .notification-message[data-icon="error"] { border-color: #b91c1c; }
    .notification-message[data-icon="info"] { border-color: #1d4ed8; }

    .swal2-popup.notification-dialog {
        border-radius: .75rem;
        color: #292524;
        font-family: inherit;
    }

    .notification-dialog .swal2-styled { border-radius: .5rem; }
</style>

<script>
    const activeNotifications = new Map();

    function showNotification({ icon = 'info', title, text, timer = { success: 3500, info: 5000 }[icon] ?? 0 }) {
        const key = JSON.stringify([icon, title, text]);
        if (activeNotifications.has(key)) return activeNotifications.get(key);

        let stack = document.getElementById('notification-stack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'notification-stack';
            stack.className = 'notification-stack';
            stack.setAttribute('role', 'status');
            stack.setAttribute('aria-atomic', 'false');
            document.body.append(stack);
        }

        const notification = document.createElement('div');
        notification.className = 'notification-message';
        notification.dataset.icon = icon;

        const heading = document.createElement('strong');
        heading.textContent = title;
        notification.append(heading);

        if (text) {
            const message = document.createElement('div');
            message.className = 'notification-text';
            message.textContent = text;
            notification.append(message);
        }

        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.type = 'button';
        closeButton.setAttribute('aria-label', 'Đóng thông báo');
        closeButton.textContent = '×';
        notification.append(closeButton);

        const dismissed = new Promise(resolve => {
            requestAnimationFrame(() => {
                let timeoutId;
                const dismiss = () => {
                    clearTimeout(timeoutId);
                    notification.remove();
                    activeNotifications.delete(key);
                    resolve();
                };
                closeButton.addEventListener('click', dismiss, { once: true });
                stack.append(notification);
                if (timer > 0) timeoutId = setTimeout(dismiss, timer);
            });
        });
        activeNotifications.set(key, dismissed);
        return dismissed;
    }

    async function confirmNotification({ title, ...options }) {
        if (typeof Swal === 'undefined') {
            if (!options.html && !options.preConfirm) {
                return { isConfirmed: window.confirm([title, options.text].filter(Boolean).join('\n\n')) };
            }
            showNotification({ icon: 'error', title: 'Không thể mở hộp xác nhận. Vui lòng tải lại trang.' });
            return { isConfirmed: false };
        }

        return Swal.fire({
            titleText: title,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            reverseButtons: true,
            focusCancel: true,
            customClass: { popup: 'notification-dialog' },
            ...options
        });
    }
</script>

@php
    $validationMessages = collect($errors->getBags())
        ->flatMap(fn ($bag) => $bag->all())
        ->unique()
        ->implode("\n");
@endphp

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const messages = [
            { icon: 'success', title: 'Thành công', text: @js(session('success')) },
            { icon: 'error', title: 'Thao tác thất bại', text: @js(session('error')) },
            { icon: 'warning', title: 'Cảnh báo', text: @js(session('warning')) },
            { icon: 'info', title: 'Thông báo', text: @js(session('info')) },
            { icon: 'error', title: 'Vui lòng kiểm tra lại thông tin', text: @js($validationMessages) }
        ];

        messages.filter(message => message.text).forEach(showNotification);

        document.querySelectorAll('[data-notification-key]').forEach(notification => {
            const key = notification.dataset.notificationKey;
            try {
                if (localStorage.getItem(key) === 'seen') return;
            } catch {}

            notification.hidden = false;
            notification.querySelector('.notification-close').addEventListener('click', () => {
                try {
                    localStorage.setItem(key, 'seen');
                } catch {}
                notification.hidden = true;
            });
        });
    });
</script>
