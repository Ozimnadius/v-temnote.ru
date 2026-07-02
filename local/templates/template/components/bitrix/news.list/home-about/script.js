(() => {
  const initHomeAboutSliders = () => {
    if (typeof window.Swiper === "undefined") {
      return false;
    }

    const sliders = document.querySelectorAll(".home-about__slider");

    sliders.forEach((slider) => {
      const swiperElement = slider.querySelector(".home-about__swiper");

      if (!swiperElement || swiperElement.dataset.homeAboutInitialized === "true") {
        return;
      }

      swiperElement.dataset.homeAboutInitialized = "true";

      new window.Swiper(swiperElement, {
        slidesPerView: 1,
        spaceBetween: 20,
        watchOverflow: true,
        pagination: {
          el: slider.querySelector(".home-about__pagination"),
          clickable: true,
        },
        breakpoints: {
          744: {
            slidesPerView: 2,
            spaceBetween: 24,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      });
    });

    return true;
  };

  const waitForSwiper = (attempt = 0) => {
    if (initHomeAboutSliders() || attempt >= 40) {
      return;
    }

    window.setTimeout(() => waitForSwiper(attempt + 1), 100);
  };

  if (document.readyState === "complete") {
    waitForSwiper();
  } else {
    window.addEventListener("load", () => waitForSwiper(), { once: true });
  }
})();
