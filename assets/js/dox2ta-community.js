(function ($) {
  function getGlobalCanvas() {
    let canvas = document.getElementById("dox2ta-confetti-global");
    if (!canvas) {
      canvas = document.createElement("canvas");
      canvas.id = "dox2ta-confetti-global";
      document.body.appendChild(canvas);
      const resize = () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
      };
      resize();
      window.addEventListener("resize", resize);
    } else {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    return canvas;
  }

  function confetti(canvas) {
    const ctx = canvas.getContext("2d");
    const W = canvas.width;
    const H = canvas.height;
    const N = 120;
    const pieces = Array.from({ length: N }).map(() => ({
      x: Math.random() * W,
      y: -Math.random() * H,
      r: 3 + Math.random() * 3,
      c: `hsl(${Math.random() * 360},85%,60%)`,
      s: 1 + Math.random() * 2,
      a: Math.random() * Math.PI,
    }));
    let running = true;
    function step() {
      if (!running) return;
      ctx.clearRect(0, 0, W, H);
      pieces.forEach((p) => {
        p.y += p.s;
        p.x += Math.sin((p.a += 0.03));
        if (p.y > H) p.y = -10;
        ctx.beginPath();
        ctx.fillStyle = p.c;
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();
      });
      requestAnimationFrame(step);
    }
    step();
    setTimeout(() => {
      running = false;
      ctx.clearRect(0, 0, W, H);
    }, 3500);
  }

  $(document).on(
    "click",
    '[data-component="dox2ta"] [data-action="join"]',
    function () {
      const $root = $(this).closest('[data-component="dox2ta"]');
      const $btn = $(this);
      const $result = $root.find(".dox2ta-result");

      if (!$btn.data("busy")) {
        $btn.data("busy", true).prop("disabled", true).addClass("is-busy");
      } else {
        return;
      }

      $.post(Dox2taCommunity.ajaxUrl, {
        action: "dox2ta_join",
        nonce: Dox2taCommunity.nonce,
      })
        .done(function (res) {
          if (res && res.success) {
            const n = res.data.join_number;
            $result
              .removeClass("dox2ta-result--error")
              .addClass("dox2ta-result--success");
            $result.html(
              "🎉 " +
                Dox2taCommunity.texts.joined.replace(
                  "%s",
                  "<strong>#" + n + "</strong>"
                )
            );
            // animate fullscreen confetti
            const canvas = getGlobalCanvas();
            confetti(canvas);
          } else {
            $result
              .removeClass("dox2ta-result--success")
              .addClass("dox2ta-result--error");
            $result.text(
              res && res.data && res.data.message
                ? res.data.message
                : "خطای ناشناخته"
            );
          }
        })
        .fail(function () {
          $result
            .removeClass("dox2ta-result--success")
            .addClass("dox2ta-result--error");
          $result.text("خطای سرور، لطفاً بعداً دوباره تلاش کنید.");
        })
        .always(function () {
          $btn
            .data("busy", false)
            .prop("disabled", false)
            .removeClass("is-busy");
        });
    }
  );
})(jQuery);
