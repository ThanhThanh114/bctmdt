// GSAP Animations cho trang About
    gsap.registerPlugin(ScrollTrigger);

    // Khởi tạo animations khi DOM loaded
    document.addEventListener('DOMContentLoaded', function () {

        // Animation cho tiêu đề chính
        gsap.fromTo('h1', {
            opacity: 0,
            y: -50,
            scale: 0.8
        }, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 1.5,
            ease: "back.out(1.7)"
        });

        // Animation cho subtitle
        gsap.fromTo('h3', {
            opacity: 0,
            y: 30
        }, {
            opacity: 1,
            y: 0,
            duration: 1,
            delay: 0.5,
            ease: "power2.out"
        });

        // Animation cho đoạn văn đầu tiên
        gsap.fromTo('.content-editor p', {
            opacity: 0,
            y: 40
        }, {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.3,
            delay: 1,
            ease: "power2.out"
        });

        // Animation cho nút "Xem thêm"
        gsap.fromTo('.cursor-pointer', {
            opacity: 0,
            scale: 0.5
        }, {
            opacity: 1,
            scale: 1,
            duration: 0.8,
            delay: 2,
            ease: "back.out(1.7)"
        });

        // Hover effect cho nút "Xem thêm"
        const xemThemBtn = document.querySelector('.cursor-pointer');
        if (xemThemBtn) {
            xemThemBtn.addEventListener('mouseenter', () => {
                gsap.to(xemThemBtn, {
                    scale: 1.1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            xemThemBtn.addEventListener('mouseleave', () => {
                gsap.to(xemThemBtn, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        }

        // Animation cho các section với ScrollTrigger
        const sections = document.querySelectorAll('.mt-8.flex.w-full');

        sections.forEach((section, index) => {
            const isReverse = section.classList.contains('sm:flex-row-reverse');
            const image = section.querySelector('img');
            const content = section.querySelector('.flex-1:not(.aspect-\\[3\\/2\\])');
            const title = section.querySelector('.text-2xl, .sm\\:text-\\[42px\\]');

            // Animation cho hình ảnh
            if (image) {
                gsap.fromTo(image, {
                    opacity: 0,
                    x: isReverse ? 100 : -100,
                    scale: 1.1
                }, {
                    opacity: 1,
                    x: 0,
                    scale: 1,
                    duration: 1.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none reverse'
                    }
                });
            }

            // Animation cho tiêu đề section
            if (title) {
                gsap.fromTo(title, {
                    opacity: 0,
                    y: 50,
                    scale: 0.9
                }, {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1,
                    delay: 0.3,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none reverse'
                    }
                });
            }

            // Animation cho nội dung text
            if (content) {
                const paragraphs = content.querySelectorAll('p, li');
                gsap.fromTo(paragraphs, {
                    opacity: 0,
                    x: isReverse ? -50 : 50
                }, {
                    opacity: 1,
                    x: 0,
                    duration: 0.8,
                    stagger: 0.2,
                    delay: 0.6,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none reverse'
                    }
                });
            }
        });

        // Animation cho border divider cuối trang
        const divider = document.querySelector('.border-b');
        if (divider) {
            gsap.fromTo(divider, {
                scaleX: 0
            }, {
                scaleX: 1,
                duration: 1.5,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: divider,
                    start: 'top 90%',
                    end: 'bottom 10%',
                    toggleActions: 'play none none reverse'
                }
            });
        }

        // Parallax effect cho các hình ảnh
        gsap.utils.toArray('img').forEach(img => {
            gsap.to(img, {
                y: -50,
                ease: "none",
                scrollTrigger: {
                    trigger: img,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        });

        // Text animation khi hover
        const textElements = document.querySelectorAll('.text-orange');
        textElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                gsap.to(el, {
                    scale: 1.05,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            el.addEventListener('mouseleave', () => {
                gsap.to(el, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Smooth scroll cho các anchor links nếu có
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: target,
                        ease: "power2.inOut"
                    });
                }
            });
        });

        // Animation cho list items
        const listItems = document.querySelectorAll('ul li');
        listItems.forEach((item, index) => {
            gsap.fromTo(item, {
                opacity: 0,
                x: -30
            }, {
                opacity: 1,
                x: 0,
                duration: 0.6,
                delay: index * 0.1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: item,
                    start: 'top 85%',
                    end: 'bottom 15%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        console.log('✨ GSAP animations loaded for About page!');
    });
