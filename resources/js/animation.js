export function initScrollReveal() {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
        );

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-clip').forEach((el) => observer.observe(el));
    }
}

export function initHeroAnimation() {
    const heroText = document.querySelector('.hero-title');
    if (!heroText) return;

    const spans = heroText.querySelectorAll('span, .hero-line');
    spans.forEach((span, i) => {
        span.style.opacity = '0';
        span.style.transform = 'translateY(30px)';
        span.style.filter = 'blur(2px)';
        setTimeout(() => {
            span.style.transition = 'opacity 0.8s cubic-bezier(0.25, 0.1, 0.25, 1), transform 0.8s cubic-bezier(0.25, 0.1, 0.25, 1), filter 0.8s ease-out';
            span.style.opacity = '1';
            span.style.transform = 'translateY(0)';
            span.style.filter = 'blur(0)';
        }, 300 + i * 200);
    });
}

export function initNavScroll() {
    const nav = document.querySelector('[data-luxury-nav]');
    if (!nav) return;

    const onScroll = () => {
        const scrolled = window.scrollY > 50;
        nav.classList.toggle('nav-scrolled', scrolled);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

export function initParallax() {
    const slowElements = document.querySelectorAll('.parallax-slow');
    const mediumElements = document.querySelectorAll('.parallax-medium');

    if (slowElements.length === 0 && mediumElements.length === 0) return;

    let ticking = false;
    const onScroll = () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrolled = window.scrollY;
                slowElements.forEach((el) => {
                    const speed = parseFloat(el.dataset.parallaxSpeed || '0.15');
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        el.style.transform = `translateY(${scrolled * speed}px)`;
                    }
                });
                mediumElements.forEach((el) => {
                    const speed = parseFloat(el.dataset.parallaxSpeed || '0.08');
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        el.style.transform = `translateY(${scrolled * speed}px)`;
                    }
                });
                ticking = false;
            });
            ticking = true;
        }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
}

export function initPageTransition() {
    const main = document.querySelector('main');
    if (main) {
        main.classList.add('page-enter');
    }

    const bar = document.getElementById('page-load-bar');
    if (bar) {
        bar.style.width = '60%';
        bar.style.opacity = '1';
        window.addEventListener('load', () => {
            bar.style.width = '100%';
            setTimeout(() => {
                bar.style.opacity = '0';
            }, 400);
        });
        if (document.readyState === 'complete') {
            bar.style.width = '100%';
            setTimeout(() => {
                bar.style.opacity = '0';
            }, 400);
        }
    }
}

export function initStaggerGrid() {
    const grids = document.querySelectorAll('[data-stagger]');
    grids.forEach((grid) => {
        const items = grid.querySelectorAll('.reveal, .reveal-scale, .reveal-left, .reveal-right');
        items.forEach((item, i) => {
            item.style.transitionDelay = `${i * 80}ms`;
        });
    });
}

export function initCounter(el, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            el.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(start).toLocaleString();
        }
    }, 16);
}
