document.addEventListener("DOMContentLoaded", function () {
  // Hero Swiper
  const heroSwiper = new Swiper('.hero-swiper', {
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

  // Modal logic
  document.getElementById("enquiryBtn").onclick = function() {
    document.getElementById("enquiryModal").style.display = "flex";
  };

  document.getElementById("closeModal").onclick = function() {
    document.getElementById("enquiryModal").style.display = "none";
  };

  window.onclick = function(e) {
    let modal = document.getElementById("enquiryModal");
    if (e.target === modal) {
      modal.style.display = "none";
    }
  };

  // Accordion logic
  document.querySelectorAll(".accordion-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      btn.classList.toggle("active");
      let panel = btn.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  });
});
window.addEventListener('scroll', function() {
  const navbar = document.querySelector('.navbar');
  const hero = document.querySelector('.hero');
  if (!navbar || !hero) return;

  // Get bottom of hero section
  const heroBottom = hero.getBoundingClientRect().bottom;

  if (heroBottom <= 0) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});