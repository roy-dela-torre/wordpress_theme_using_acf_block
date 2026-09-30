const testimonialSliderBlockLoadedEvent = new Event('testimonialSliderBlockLoadedEvent');

document.addEventListener("DOMContentLoaded", initTestimonialSwipers);
document.addEventListener("testimonialSliderBlockLoadedEvent", initTestimonialSwipers);

const TESTIMONIAL_DESKTOP = "(min-width: 1024px)";

function initTestimonialSwipers() {
    document.querySelectorAll(".testimonial-swiper").forEach(setupTestimonialSwiper);
}

function setupTestimonialSwiper(container) {
    if (container.dataset.testimonialReady) {
        return;
    }
    container.dataset.testimonialReady = "true";

    const wrapper = container.querySelector(".swiper-wrapper");
    const pagination = container.parentElement.querySelector(".swiper-pagination");
    const count = wrapper.querySelectorAll(".swiper-slide").length;

    if (count === 0) {
        return;
    }

    if (count === 4) {
        wrapper.querySelectorAll(".swiper-slide").forEach((slide) => {
            wrapper.appendChild(slide.cloneNode(true));
        });
    }

    const desktop = window.matchMedia(TESTIMONIAL_DESKTOP);
    let swiper = null;

    const build = () => {
        if (swiper) {
            swiper.destroy(true, true);
        }
        // Swiper's destroy leaves rendered bullets behind; clear them so a
        // rebuild without pagination doesn't show orphaned bullets.
        if (pagination) {
            pagination.innerHTML = "";
        }
        swiper = new Swiper(container, testimonialConfig(count, desktop.matches, pagination));
    };

    build();
    desktop.addEventListener("change", build);
}

function testimonialConfig(count, isDesktop, pagination) {
    const config = {
        grabCursor: true,
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: pagination,
            clickable: true,
        },
    };

    if (count === 1) {
        config.loop = false;
        config.autoplay = false;
        config.pagination = false;
        return config;
    }

    if (count === 3 && isDesktop) {
        config.slidesPerView = 3;
        config.loop = false;
        config.autoplay = false;
        config.pagination = false;
        config.initialSlide = 1;
        config.allowTouchMove = false;
        return config;
    }

    if (count === 4) {
        if (isDesktop) {
            config.slidesPerView = 3;
        }
        config.on = {
            paginationRender: (swiper, el) => limitTestimonialBullets(el, count),
            paginationUpdate: (swiper, el) => showTestimonialBullet(el, swiper.realIndex % count),
        };
        return config;
    }

    if (count >= 5 && isDesktop) {
        config.slidesPerView = 3;
    }

    return config;
}

function limitTestimonialBullets(el, keep) {
    el.querySelectorAll(".swiper-pagination-bullet").forEach((bullet, index) => {
        bullet.style.display = index < keep ? "" : "none";
    });
}

function showTestimonialBullet(el, active) {
    el.querySelectorAll(".swiper-pagination-bullet").forEach((bullet, index) => {
        bullet.classList.toggle("swiper-pagination-bullet-active", index === active);
    });
}
