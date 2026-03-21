document.addEventListener("DOMContentLoaded", function () {
  //dialog open/close
  const openTrigger = document.querySelectorAll(".js-showDialog");
  openTrigger.forEach((figure) => {
    figure.addEventListener("click", () => {
      const dialogId = figure.getAttribute("data-dialog");
      const dialog = document.getElementById(dialogId);
      dialog.showModal();
      dialog.scroll({
        top: 0,
        behavior: "smooth",
      });
      dialog.style.display = "grid"; // 一旦閉じた後に再度開けなくならないよう
      dialog.addEventListener("click", () => {
        if (dialog.open) {
          dialog.close();
          dialog.addEventListener("close", async (e) => {
            // 閉じるアニメーションの終了を待つ
            await waitDialogAnimation(e.target);
            dialog.style.display = "none";
          });
          const waitDialogAnimation = (dialog) => Promise.allSettled(Array.from(dialog.getAnimations()).map((animation) => animation.finished));
        }
      });
    });
  });

  //smooth scroll
  var scrollAnchor = [].slice.call(document.querySelectorAll('a[href^="#"]'));
  scrollAnchor.forEach(function (scrollTrigger) {
    scrollTrigger.addEventListener("click", function (e) {
      const duration = 300;
      const href = scrollTrigger.getAttribute("href");
      const currentPostion = document.documentElement.scrollTop || document.body.scrollTop;
      const targetElement = document.getElementById(href.replace("#", ""));
      if (targetElement) {
        e.preventDefault(); //デフォルトの動作をキャンセル
        e.stopPropagation(); //インターフェイスのイベントのさらなる伝播を阻止
        const targetPosition = window.pageYOffset + targetElement.getBoundingClientRect().top;
        const sTime = performance.now();
        const loop = function (nowTime) {
          const time = nowTime - sTime;
          const nTime = time / duration;
          if (nTime < 1) {
            window.scrollTo(0, currentPostion + (targetPosition - currentPostion) * nTime);
            requestAnimationFrame(loop);
          } else {
            window.scrollTo(0, targetPosition);
          }
        };
        requestAnimationFrame(loop);
      }
      return false;
    });
  });

  //iamge fadein
  const fadeElements = document.querySelectorAll(".js-fadein");
  const observer = new IntersectionObserver((elements) => {
    elements.forEach((element) => {
      if (element.isIntersecting) {
        element.target.classList.add("isActive");
      }
    });
  });
  fadeElements.forEach((el) => observer.observe(el));
});
