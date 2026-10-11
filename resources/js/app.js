const modalAnimations = new WeakMap();
const modalCancelHandlers = new WeakSet();

const openModal = (dialog) => {
    if (dialog.open) return;

    dialog.removeAttribute('data-closing');
    dialog.showModal();

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const animation = dialog.animate(
        [
            { opacity: 0, transform: 'translateY(8px) scale(0.98)' },
            { opacity: 1, transform: 'translateY(0) scale(1)' },
        ],
        { duration: 220, easing: 'cubic-bezier(0.2, 0.8, 0.2, 1)', fill: 'both' },
    );

    modalAnimations.set(dialog, animation);
    animation.onfinish = () => {
        animation.cancel();
        modalAnimations.delete(dialog);
    };
};

const closeModal = (dialog) => {
    if (!dialog.open || dialog.hasAttribute('data-closing')) return;

    const openingAnimation = modalAnimations.get(dialog);
    if (openingAnimation) {
        openingAnimation.cancel();
        modalAnimations.delete(dialog);
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        dialog.close();
        return;
    }

    dialog.setAttribute('data-closing', '');

    const animation = dialog.animate(
        [
            { opacity: 1, transform: 'translateY(0) scale(1)' },
            { opacity: 0, transform: 'translateY(8px) scale(0.98)' },
        ],
        { duration: 220, easing: 'cubic-bezier(0.4, 0, 1, 1)', fill: 'both' },
    );

    modalAnimations.set(dialog, animation);
    animation.onfinish = () => {
        if (dialog.open) dialog.close();
        animation.cancel();
        modalAnimations.delete(dialog);
        dialog.removeAttribute('data-closing');
    };
};

document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-modal-open]');
    if (openButton) {
        const dialog = document.getElementById(openButton.dataset.modalOpen);
        if (dialog instanceof HTMLDialogElement && dialog.matches('[data-modal]')) {
            if (!modalCancelHandlers.has(dialog)) {
                dialog.addEventListener('cancel', (cancelEvent) => {
                    cancelEvent.preventDefault();
                    closeModal(dialog);
                });
                modalCancelHandlers.add(dialog);
            }

            openModal(dialog);
        }
    }

    const closeButton = event.target.closest('[data-modal-close]');
    if (closeButton) {
        const dialog = closeButton.closest('dialog[data-modal]');
        if (dialog instanceof HTMLDialogElement) closeModal(dialog);
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

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    const isModalConfirmation = form.closest('dialog[data-modal]') || form.hasAttribute('data-modal-confirm-form');
    if (!isModalConfirmation) return;

    if (form.dataset.modalSubmitting === 'true') {
        event.preventDefault();
        return;
    }

    form.dataset.modalSubmitting = 'true';
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-violation-form]');
    if (!form) return;

    const queryInput = form.querySelector('[data-student-query]');
    const dateInput = form.querySelector('[data-incident-date]');
    const resultList = form.querySelector('[data-student-results]');
    const placementInput = form.querySelector('[data-placement-id]');
    const categorySelect = form.querySelector('[data-category-select]');
    const violationSelect = form.querySelector('[data-violation-select]');
    const pointsPreview = form.querySelector('[data-points-preview]');
    const resultDetails = [...form.querySelectorAll('[data-student-details]')];
    const placeholder = form.querySelector('[data-student-placeholder]');
    let requestSequence = 0;
    let debounceTimer;

    const clearSelection = () => {
        placementInput.value = '';
        resultDetails.forEach((element) => element.classList.add('hidden'));
        placeholder.classList.remove('hidden');
    };

    const renderResults = (items) => {
        resultList.replaceChildren();
        resultList.classList.remove('hidden');

        if (items.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'px-3 py-3 text-sm text-gray-500';
            empty.textContent = 'Tidak ada siswa dengan penempatan pada tanggal tersebut.';
            resultList.append(empty);
            return;
        }

        items.forEach((item) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'block w-full border-b border-gray-100 px-3 py-3 text-left last:border-0 hover:bg-gray-50 focus:bg-gray-50 focus:outline-none';
            button.setAttribute('role', 'option');
            const name = document.createElement('span');
            name.className = 'block text-sm font-medium text-gray-900';
            name.textContent = item.student.name;
            const description = document.createElement('span');
            description.className = 'mt-1 block text-xs text-gray-500';
            const classLabel = [item.class.name, item.class.level, item.class.major].filter(Boolean).join(' · ');
            description.textContent = `${item.student.nisn} · ${classLabel} · ${item.academic_year}`;
            button.append(name, description);
            button.addEventListener('click', () => {
                placementInput.value = item.placement_id;
                form.querySelector('[data-student-name]').textContent = item.student.name;
                form.querySelector('[data-student-nisn]').textContent = item.student.nisn;
                form.querySelector('[data-student-class]').textContent = classLabel;
                form.querySelector('[data-student-year]').textContent = item.academic_year;
                placeholder.classList.add('hidden');
                resultDetails.forEach((element) => element.classList.remove('hidden'));
                resultList.classList.add('hidden');
            });
            resultList.append(button);
        });
    };

    const searchStudents = async () => {
        const query = queryInput.value.trim();
        if (query.length < 2 || !dateInput.value) {
            resultList.classList.add('hidden');
            return;
        }

        const sequence = ++requestSequence;
        const url = new URL(form.dataset.searchUrl, window.location.origin);
        url.searchParams.set('q', query);
        url.searchParams.set('tanggal_pelanggaran', dateInput.value);
        const loading = document.createElement('p');
        loading.className = 'px-3 py-3 text-sm text-gray-500';
        loading.textContent = 'Mencari siswa…';
        resultList.replaceChildren(loading);
        resultList.classList.remove('hidden');

        try {
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Pencarian gagal. Periksa tanggal kejadian.');
            const items = await response.json();
            if (sequence === requestSequence) renderResults(items);
        } catch (error) {
            if (sequence !== requestSequence) return;
            const message = document.createElement('p');
            message.className = 'px-3 py-3 text-sm text-gray-600';
            message.textContent = error.message;
            resultList.replaceChildren(message);
            resultList.classList.remove('hidden');
        }
    };

    const filterViolations = () => {
        const categoryId = categorySelect.value;
        const violationOptions = [...violationSelect.options].filter((option) => option.value);
        violationOptions.forEach((option) => {
            const isVisible = option.dataset.categoryId === categoryId;
            option.hidden = !isVisible;
            option.disabled = !isVisible;
        });
        const hasAvailableViolations = violationOptions.some((option) => option.dataset.categoryId === categoryId);
        const placeholder = violationSelect.querySelector('[data-violation-placeholder]');
        placeholder.textContent = !categoryId
            ? 'Pilih kategori terlebih dahulu'
            : hasAvailableViolations
                ? 'Pilih jenis pelanggaran'
                : 'Tidak ada jenis pelanggaran aktif pada kategori ini';
        if (!categoryId || violationSelect.selectedOptions[0]?.dataset.categoryId !== categoryId) {
            violationSelect.value = '';
        }
        pointsPreview.textContent = violationSelect.selectedOptions[0]?.dataset.points
            ? `${violationSelect.selectedOptions[0].dataset.points} poin`
            : 'Pilih jenis pelanggaran';
    };

    queryInput.addEventListener('input', () => {
        clearSelection();
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(searchStudents, 250);
    });
    dateInput.addEventListener('change', () => {
        clearSelection();
        searchStudents();
    });
    categorySelect.addEventListener('change', filterViolations);
    violationSelect.addEventListener('change', filterViolations);

    const previousViolation = violationSelect.selectedOptions[0];
    if (previousViolation?.dataset.categoryId) categorySelect.value = previousViolation.dataset.categoryId;
    filterViolations();
});
