(function () {
  const header = document.querySelector(".site-header");
  if (!header) return;

  const updateHeader = () => {
    header.style.background = window.scrollY > 24
      ? "rgba(5, 5, 5, 0.92)"
      : "rgba(5, 5, 5, 0.74)";
  };

  updateHeader();
  window.addEventListener("scroll", updateHeader, { passive: true });
})();
