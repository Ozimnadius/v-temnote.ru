(function () {
  var isBound = false;

  function bindGalleryDetailFancybox() {
    if (isBound || !window.Fancybox) {
      return;
    }

    Fancybox.bind('[data-fancybox="gallery-detail"]', {});
    isBound = true;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindGalleryDetailFancybox);
  } else {
    bindGalleryDetailFancybox();
  }

  window.addEventListener('load', bindGalleryDetailFancybox);
})();
