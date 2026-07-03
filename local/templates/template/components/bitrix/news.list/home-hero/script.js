(() => {
  const initHomeHeroSliders = () => {
    if (typeof window.Swiper === "undefined") {
      return false;
    }

    const sliders = document.querySelectorAll(".home-hero__slider");

    sliders.forEach((slider) => {
      const swiperElement = slider.querySelector(".home-hero__swiper");

      if (!swiperElement || swiperElement.dataset.homeHeroInitialized === "true") {
        return;
      }

      swiperElement.dataset.homeHeroInitialized = "true";

      new window.Swiper(swiperElement, {
        slidesPerView: 1,
        loop: true,
        effect: "fade",
        autoplay: {
          delay: 5000,
        },
        pagination: {
          el: slider.querySelector(".home-hero__pagination"),
          clickable: true,
        },
        navigation: {
          prevEl: slider.querySelector(".home-hero__arrow--prev"),
          nextEl: slider.querySelector(".home-hero__arrow--next"),
        },
      });
    });

    return true;
  };

  const waitForSwiper = (attempt = 0) => {
    if (initHomeHeroSliders() || attempt >= 40) {
      return;
    }

    window.setTimeout(() => waitForSwiper(attempt + 1), 100);
  };

  if (document.readyState === "complete") {
    waitForSwiper();
  } else {
    window.addEventListener("load", () => waitForSwiper(), {once: true});
  }
})();
