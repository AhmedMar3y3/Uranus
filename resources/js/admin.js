import './bootstrap';

const openModal = (modal) => {
    if (!modal) {
        return;
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    modal.querySelector('input, select, textarea, button')?.focus();
};

const closeModal = (modal) => {
    if (!modal) {
        return;
    }

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
};

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-modal-target]');

    if (trigger) {
        openModal(document.getElementById(trigger.dataset.modalTarget));
        return;
    }

    if (event.target.matches('[data-modal-close]')) {
        closeModal(event.target.closest('.modal'));
        return;
    }

    if (event.target.classList.contains('modal')) {
        closeModal(event.target);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('.modal.is-open').forEach(closeModal);
});
