document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-modal-open]');
    if (openButton) {
        const dialog = document.getElementById(openButton.dataset.modalOpen);
        if (dialog instanceof HTMLDialogElement) dialog.showModal();
    }

    if (event.target.closest('[data-modal-close]')) {
        event.target.closest('dialog')?.close();
    }

    const toastButton = event.target.closest('[data-toast-trigger]');
    if (toastButton) {
        const toast = document.getElementById(toastButton.dataset.toastTrigger);
        if (toast) {
            toast.classList.remove('translate-y-2', 'opacity-0', 'pointer-events-none');
            window.setTimeout(() => toast.classList.add('translate-y-2', 'opacity-0', 'pointer-events-none'), 3200);
        }
    }

    const toastClose = event.target.closest('[data-toast-close]');
    if (toastClose) toastClose.closest('[data-toast]')?.classList.add('translate-y-2', 'opacity-0', 'pointer-events-none');
});
