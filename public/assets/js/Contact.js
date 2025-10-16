// GSAP Timeline và Animations
    gsap.registerPlugin(ScrollTrigger, TextPlugin);

    // Thiết lập GSAP
    gsap.set("body", {
        overflow: "hidden"
    });

    // Main Timeline
    const mainTl = gsap.timeline({
        onComplete: () => {
            gsap.set("body", {
                overflow: "auto"
            });
        }
    });

    // Hero Section Animations
    const heroTl = gsap.timeline();

    heroTl.fromTo('#heroTitle', {
        opacity: 0,
        y: 100,
        scale: 0.8,
        rotationX: -45
    }, {
        opacity: 1,
        y: 0,
        scale: 1,
        rotationX: 0,
        duration: 1.5,
        ease: "back.out(1.7)"
    });

    heroTl.fromTo('#heroSubtitle', {
        opacity: 0,
        y: 50,
        scale: 0.9
    }, {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 1,
        ease: "power3.out"
    }, "-=0.8");

    // Stats Animation với Counter
    heroTl.fromTo('.stat-item', {
        opacity: 0,
        y: 80,
        scale: 0.5,
        rotationY: -180
    }, {
        opacity: 1,
        y: 0,
        scale: 1,
        rotationY: 0,
        duration: 1,
        stagger: 0.2,
        ease: "back.out(2)"
    }, "-=0.5");

    // Animated Counters
    heroTl.call(() => {
        document.querySelectorAll('.stat-number').forEach((counter, index) => {
            const target = parseInt(counter.getAttribute('data-target'));
            const isSpecial = counter.textContent.includes('★') || counter.textContent.includes('/7');

            if (!isSpecial) {
                gsap.fromTo(counter, {
                    textContent: 0
                }, {
                    textContent: target,
                    duration: 2,
                    ease: "power2.out",
                    snap: {
                        textContent: 1
                    },
                    onUpdate: function () {
                        if (target === 150) {
                            counter.textContent = Math.ceil(this.targets()[0].textContent) +
                                '+';
                        } else if (target === 24) {
                            counter.textContent = Math.ceil(this.targets()[0].textContent) +
                                '/7';
                        }
                    }
                });
            } else if (counter.textContent.includes('★')) {
                gsap.fromTo(counter, {
                    textContent: 0
                }, {
                    textContent: 5,
                    duration: 2,
                    ease: "power2.out",
                    snap: {
                        textContent: 1
                    },
                    onUpdate: function () {
                        counter.textContent = Math.ceil(this.targets()[0].textContent) + '★';
                    }
                });
            }
        });
    }, null, "-=1");

    mainTl.add(heroTl);

    // Section Headers Animation
    gsap.fromTo('.section-header', {
        opacity: 0,
        y: 60,
        scale: 0.9
    }, {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: '.section-header',
            start: 'top 85%',
            end: 'bottom 15%',
            toggleActions: 'play none none reverse'
        }
    });

    // Contact Cards Staggered Animation
    ScrollTrigger.batch('.contact-card', {
        onEnter: (elements) => {
            gsap.fromTo(elements, {
                opacity: 0,
                y: 100,
                scale: 0.8,
                rotationX: -45
            }, {
                opacity: 1,
                y: 0,
                scale: 1,
                rotationX: 0,
                duration: 1,
                stagger: 0.15,
                ease: "back.out(1.7)"
            });
        },
        onLeave: (elements) => {
            gsap.to(elements, {
                opacity: 0.7,
                scale: 0.95,
                duration: 0.5
            });
        },
        onEnterBack: (elements) => {
            gsap.to(elements, {
                opacity: 1,
                scale: 1,
                duration: 0.5
            });
        }
    });

    // Form Section Animations
    gsap.fromTo('#formInfo', {
        opacity: 0,
        x: -100,
        rotationY: -25
    }, {
        opacity: 1,
        x: 0,
        rotationY: 0,
        duration: 1.2,
        ease: "power3.out",
        scrollTrigger: {
            trigger: '#formInfo',
            start: 'top 80%',
            end: 'bottom 20%',
            toggleActions: 'play none none reverse'
        }
    });

    gsap.fromTo('#mainForm', {
        opacity: 0,
        x: 100,
        rotationY: 25
    }, {
        opacity: 1,
        x: 0,
        rotationY: 0,
        duration: 1.2,
        ease: "power3.out",
        scrollTrigger: {
            trigger: '#mainForm',
            start: 'top 80%',
            end: 'bottom 20%',
            toggleActions: 'play none none reverse'
        }
    });

    // Info Items Stagger Animation
    ScrollTrigger.batch('.info-item', {
        onEnter: (elements) => {
            gsap.fromTo(elements, {
                opacity: 0,
                x: -50,
                scale: 0.8
            }, {
                opacity: 1,
                x: 0,
                scale: 1,
                duration: 0.8,
                stagger: 0.1,
                ease: "back.out(1.7)"
            });
        }
    });

    // FAQ Animation
    gsap.fromTo('#faqContainer', {
        opacity: 0,
        y: 50
    }, {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: '#faqSection',
            start: 'top 80%',
            end: 'bottom 20%',
            toggleActions: 'play none none reverse'
        }
    });

    // Form Input Interactions
    document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(input => {
        input.addEventListener('focus', () => {
            gsap.to(input, {
                scale: 1.03,
                duration: 0.3,
                ease: "power2.out"
            });

            gsap.to(input.previousElementSibling, {
                color: 'var(--primary-orange)',
                scale: 1.05,
                duration: 0.3
            });
        });

        input.addEventListener('blur', () => {
            gsap.to(input, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out"
            });

            gsap.to(input.previousElementSibling, {
                color: 'var(--text-dark)',
                scale: 1,
                duration: 0.3
            });
        });
    });

    // Submit Button Animation
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            if (this.closest('form').checkValidity()) {
                // Button Animation
                gsap.to(this, {
                    scale: 0.95,
                    duration: 0.1,
                    ease: "power2.out",
                    yoyo: true,
                    repeat: 1
                });

                // Loading State
                const originalHTML = this.innerHTML;
                this.innerHTML = '<div class="loading"></div> Đang gửi...';
                this.disabled = true;

                // Simulate loading
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Đã gửi thành công!';
                    gsap.to(this, {
                        backgroundColor: 'var(--primary-green)',
                        duration: 0.5,
                        ease: "power2.out"
                    });

                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        gsap.to(this, {
                            backgroundColor: 'var(--primary-orange)',
                            duration: 0.5
                        });
                    }, 2000);
                }, 1500);
            }
        });
    }

    // Card Hover Effects với GSAP
    document.querySelectorAll('.contact-card').forEach(card => {
        const icon = card.querySelector('.contact-icon');
        const tl = gsap.timeline({
            paused: true
        });

        tl.to(card, {
            y: -15,
            scale: 1.03,
            duration: 0.4,
            ease: "power2.out"
        })
            .to(icon, {
                scale: 1.1,
                rotationY: 180,
                duration: 0.4,
                ease: "back.out(1.7)"
            }, 0);

        card.addEventListener('mouseenter', () => tl.play());
        card.addEventListener('mouseleave', () => tl.reverse());
    });

    // Parallax Effects
    gsap.to('#hero', {
        yPercent: -30,
        ease: "none",
        scrollTrigger: {
            trigger: '#hero',
            start: 'top bottom',
            end: 'bottom top',
            scrub: 1
        }
    });

    // Text Animation on Scroll
    gsap.utils.toArray('.section-header h2').forEach(heading => {
        gsap.fromTo(heading, {
            opacity: 0.5,
            scale: 0.8,
            filter: 'blur(5px)'
        }, {
            opacity: 1,
            scale: 1,
            filter: 'blur(0px)',
            duration: 1,
            ease: "power3.out",
            scrollTrigger: {
                trigger: heading,
                start: 'top 85%',
                end: 'bottom 15%',
                toggleActions: 'play none none reverse'
            }
        });
    });

    // FAQ Toggle Function với GSAP
    function toggleFAQ(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');
        const isActive = answer.classList.contains('active');

        // Close all other FAQs
        document.querySelectorAll('.faq-answer.active').forEach(activeAnswer => {
            if (activeAnswer !== answer) {
                activeAnswer.classList.remove('active');
                const activeIcon = activeAnswer.previousElementSibling.querySelector('i');
                gsap.to(activeIcon, {
                    rotation: 0,
                    duration: 0.4,
                    ease: "power2.out"
                });
            }
        });

        if (isActive) {
            answer.classList.remove('active');
            gsap.to(icon, {
                rotation: 0,
                duration: 0.4,
                ease: "power2.out"
            });
        } else {
            answer.classList.add('active');
            gsap.to(icon, {
                rotation: 180,
                duration: 0.4,
                ease: "power2.out"
            });

            // Animate answer content
            gsap.fromTo(answer.querySelector('p'), {
                opacity: 0,
                y: 20
            }, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                delay: 0.2,
                ease: "power2.out"
            });
        }
    }

    // Live Chat Function
    function startLiveChat() {
        const chatBtn = event.target;
        gsap.to(chatBtn, {
            scale: 1.2,
            duration: 0.2,
            ease: "power2.out",
            yoyo: true,
            repeat: 1,
            onComplete: () => {
                // Create popup animation
                const popup = document.createElement('div');
                popup.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0);
                background: linear-gradient(135deg, var(--primary-orange), var(--primary-green));
                color: white;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                z-index: 10000;
                text-align: center;
                max-width: 400px;
            `;
                popup.innerHTML = `
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <h3>Live Chat</h3>
                <p>Tính năng chat trực tiếp đang được phát triển. Vui lòng liên hệ hotline <strong>19006067</strong> để được hỗ trợ tốt nhất!</p>
                <button onclick="this.parentNode.remove()" style="
                    background: white;
                    color: var(--primary-orange);
                    border: none;
                    padding: 10px 25px;
                    border-radius: 25px;
                    margin-top: 20px;
                    font-weight: 600;
                    cursor: pointer;
                ">Đóng</button>
            `;

                document.body.appendChild(popup);

                gsap.to(popup, {
                    scale: 1,
                    duration: 0.5,
                    ease: "back.out(1.7)"
                });

                setTimeout(() => {
                    if (popup.parentNode) {
                        gsap.to(popup, {
                            scale: 0,
                            opacity: 0,
                            duration: 0.3,
                            onComplete: () => popup.remove()
                        });
                    }
                }, 5000);
            }
        });
    }

    // Smooth Scroll
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

    console.log('🚀 GSAP Contact Page với Brand Colors loaded successfully!');
