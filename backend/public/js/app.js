const motionToggle = document.querySelector('#toggle-motion-btn');
if (motionToggle) {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let reduced = prefersReducedMotion;

    try {
        reduced = JSON.parse(localStorage.getItem('clinica_reduced_motion')) ?? prefersReducedMotion;
    } catch {
        // La preferencia del sistema también funciona cuando el almacenamiento está bloqueado.
    }

    function applyMotionPreference() {
        document.documentElement.classList.toggle('reduced-motion', reduced);
        motionToggle.setAttribute('aria-pressed', String(reduced));
        motionToggle.textContent = reduced ? 'Activar animaciones' : 'Pausar animaciones';
    }

    motionToggle.hidden = false;
    applyMotionPreference();
    motionToggle.addEventListener('click', () => {
        reduced = !reduced;
        applyMotionPreference();

        try {
            localStorage.setItem('clinica_reduced_motion', JSON.stringify(reduced));
        } catch {
            // La preferencia se aplica en la página abierta.
        }
    });
}

document.querySelectorAll('[data-dismiss-alert]').forEach((button) => {
    button.addEventListener('click', () => button.closest('[role="status"]')?.remove());
});
