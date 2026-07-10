(function () {
  var isBound = false;

  function bindRulesVideoFancybox() {
    if (isBound || !window.Fancybox) {
      return;
    }

    Fancybox.bind('[data-fancybox="rules-video"]', {});
    isBound = true;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindRulesVideoFancybox);
  } else {
    bindRulesVideoFancybox();
  }

  window.addEventListener('load', bindRulesVideoFancybox);
})();
