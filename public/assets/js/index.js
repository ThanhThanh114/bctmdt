
    // <!-- ======================== 2. SLIDER KHUYẾN MÃI ======================== -->
document.addEventListener("DOMContentLoaded", function () {
  var swiper = new Swiper(".mySwiper2", {
    slidesPerView: 3,   // hoặc 4
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      320: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
      1280: { slidesPerView: 4 }
    }
  });
});

    // <!-- ======================== 2. SLIDER KHUYẾN MÃI ======================== -->
