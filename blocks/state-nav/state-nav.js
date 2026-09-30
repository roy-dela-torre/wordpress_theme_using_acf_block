function initStateNavAccordions() {

    let blocks = document.querySelectorAll(".lp-state-nav");

    blocks.forEach((block) => {

        const cards = block.querySelectorAll(".lp-state-nav__card");

        cards.forEach((card) => {

            const trigger = card.querySelector('.lp-state-nav__card-trigger');

            if (!trigger) return;

            trigger.addEventListener('click', () => {
                const isOpen = card.classList.toggle('open');
                trigger.setAttribute('aria-expanded', isOpen);

                if (!isOpen) return;

                cards.forEach((other) => {
                    if (other === card || !other.classList.contains('open')) return;
                    other.classList.remove('open');
                    other.querySelector('.lp-state-nav__card-trigger').setAttribute('aria-expanded', false);
                });
            });

        });

    });

}


document.addEventListener("DOMContentLoaded", initStateNavAccordions);
