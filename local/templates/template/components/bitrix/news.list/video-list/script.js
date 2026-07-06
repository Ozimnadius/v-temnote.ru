(function () {
  function initVideoList(root) {
    var scope = root || document;
    var mediaItems = scope.querySelectorAll(".video-list__media");

    mediaItems.forEach(function (media) {
      if (media.dataset.videoListInited === "true") {
        return;
      }

      var video = media.querySelector(".video-list__video");

      if (!video) {
        return;
      }

      var pauseOtherVideos = function () {
        scope.querySelectorAll(".video-list__video").forEach(function (otherVideo) {
          if (otherVideo !== video && !otherVideo.paused) {
            otherVideo.pause();
          }
        });
      };

      var syncPlayState = function () {
        media.classList.toggle("video-list__media--playing", !video.paused && !video.ended);
      };

      var handlePlay = function () {
        pauseOtherVideos();
        syncPlayState();
      };

      media.dataset.videoListInited = "true";
      video.addEventListener("play", handlePlay);
      video.addEventListener("playing", syncPlayState);
      video.addEventListener("pause", syncPlayState);
      video.addEventListener("ended", syncPlayState);
      video.addEventListener("emptied", syncPlayState);

      syncPlayState();
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initVideoList(document);
    });
  } else {
    initVideoList(document);
  }

  if (window.BX && BX.addCustomEvent) {
    BX.addCustomEvent("onAjaxSuccess", function () {
      initVideoList(document);
    });
  }
})();
