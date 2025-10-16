document.addEventListener("DOMContentLoaded", function() {
    // Swiper initialization
    var swiper = new Swiper(".mySwiper2", {
        slidesPerView: 3,
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
            320: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            },
            1280: {
                slidesPerView: 4
            },
        },
    });

    // Switch location functionality
    const switchBtn = document.querySelector('.switch-location');
    const startSelect = document.querySelector('select[name="start"]');
    const endSelect = document.querySelector('select[name="end"]');

    if (switchBtn && startSelect && endSelect) {
        switchBtn.addEventListener('click', function() {
            const startValue = startSelect.value;
            const endValue = endSelect.value;

            startSelect.value = endValue;
            endSelect.value = startValue;

            // Add rotation animation to icon
            const icon = this.querySelector('.fas');
            if (icon) {
                icon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    icon.style.transform = 'rotate(0deg)';
                }, 300);
            }
        });
    }

    // Enhanced dropdown styling
    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('focus', function() {
            this.style.borderColor = '#ff5722';
            this.style.boxShadow = '0 0 0 3px rgba(255, 87, 34, 0.1)';
        });

        select.addEventListener('blur', function() {
            this.style.borderColor = '#e5e7eb';
            this.style.boxShadow = 'none';
        });
    });

    // User dropdown functionality
    function toggleUserMenu() {
        const userMenu = document.getElementById('user-menu');
        if (userMenu) {
            userMenu.classList.toggle('active');
        }
    }

    // Close user menu when clicking outside
    document.addEventListener('click', (e) => {
        const userMenu = document.getElementById('user-menu');
        const userBtn = document.querySelector('.user-btn');

        if (userMenu && userBtn && !userBtn.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove('active');
        }
    });

    // Make toggleUserMenu available globally
    window.toggleUserMenu = toggleUserMenu;
});
