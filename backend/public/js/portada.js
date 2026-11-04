document.querySelectorAll('[data-slider]').forEach((slider) => {
    const slides = [...slider.querySelectorAll('[data-slide]')];
    const controls = slider.querySelector('[data-slider-controls]');
    let current = 0;

    controls.hidden = slides.length < 2;
    controls.addEventListener('click', (event) => {
        const button = event.target.closest('[data-slide-step]');
        if (!button) return;

        slides[current].hidden = true;
        current = (current + Number(button.dataset.slideStep) + slides.length) % slides.length;
        slides[current].hidden = false;
        slider.querySelector('[data-slide-number]').textContent = String(current + 1).padStart(2, '0');
        slider.querySelector('[data-slide-location]').textContent = slides[current].dataset.location;
    });
});

document.querySelectorAll('[data-protocols]').forEach((group) => {
    const buttons = [...group.querySelectorAll('[data-protocol-button]')];
    const panels = [...group.querySelectorAll('[data-protocol-panel]')];

    function selectPanel(selected) {
        buttons.forEach((button, index) => {
            button.setAttribute('aria-pressed', String(index === selected));
            panels[index].hidden = index !== selected;
        });
    }

    group.querySelector('[data-protocol-controls]').hidden = false;
    buttons.forEach((button, index) => button.addEventListener('click', () => selectPanel(index)));
    selectPanel(0);
});

const gallery = document.querySelector('[data-gallery]');
if (gallery) {
    const filters = gallery.querySelector('[data-gallery-filters]');
    const cards = [...gallery.querySelectorAll('[data-gallery-category]')];

    filters.hidden = false;
    filters.addEventListener('click', (event) => {
        const button = event.target.closest('[data-gallery-filter]');
        if (!button) return;

        filters.querySelectorAll('button').forEach((filter) => {
            filter.setAttribute('aria-pressed', String(filter === button));
        });
        const category = button.dataset.galleryFilter;
        const visible = cards.filter((card) => {
            card.hidden = Boolean(category) && card.dataset.galleryCategory !== category;
            return !card.hidden;
        });
        gallery.querySelector('[data-gallery-count]').textContent = String(visible.length);
    });
}

const dialog = document.querySelector('[data-gallery-dialog]');
if (dialog && typeof dialog.showModal === 'function') {
    const image = dialog.querySelector('[data-gallery-image]');
    const orientation = dialog.querySelector('[data-gallery-orientation]');

    document.querySelectorAll('[data-gallery-item]').forEach((link) => {
        link.addEventListener('click', (event) => {
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

            event.preventDefault();
            const item = JSON.parse(link.dataset.galleryItem);
            image.src = item.image;
            image.alt = item.title;
            dialog.querySelectorAll('[data-gallery-value]').forEach((field) => {
                field.textContent = item[field.dataset.galleryValue];
            });
            dialog.showModal();
        });
    });

    orientation.addEventListener('click', () => {
        const portrait = dialog.classList.toggle('gallery-portrait');
        orientation.setAttribute('aria-pressed', String(portrait));
        orientation.textContent = portrait ? 'Encuadre panorámico' : 'Encuadre vertical';
    });
}
