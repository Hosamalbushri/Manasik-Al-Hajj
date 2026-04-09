import '../css/app.css';

function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        const existing = document.querySelector(`script[src="${src}"]`);
        if (existing) {
            if (existing.dataset.loaded === 'true') return resolve();
            existing.addEventListener('load', () => resolve(), { once: true });
            existing.addEventListener('error', () => reject(new Error(`Failed loading ${src}`)), { once: true });
            return;
        }

        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        script.onload = () => {
            script.dataset.loaded = 'true';
            resolve();
        };
        script.onerror = () => reject(new Error(`Failed loading ${src}`));
        document.head.appendChild(script);
    });
}

function loadStylesheetOnce(href) {
    if (document.querySelector(`link[href="${href}"]`)) return;
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
}

function initNavbar() {
    const burger = document.getElementById('webBurgerBtn');
    const navLinks = document.getElementById('webNavLinks');
    const overlay = document.getElementById('webOverlay');
    const body = document.body;
    const langDropdown = document.getElementById('webLangDropdown');
    const langBtn = document.getElementById('webLangBtn');
    const langMenu = document.getElementById('webLangMenu');
    const loginBtn = document.getElementById('webLoginBtn');
    const navbar = document.querySelector('.web-hajj-navbar');

    if (!burger || !navLinks || !overlay) return;

    const closeMenu = () => {
        navLinks.classList.remove('active');
        overlay.classList.remove('active');
        body.style.overflow = '';
        burger.innerHTML = '<i class="fas fa-bars"></i>';
    };

    const toggleMobileMenu = () => {
        navLinks.classList.toggle('active');
        overlay.classList.toggle('active');

        if (navLinks.classList.contains('active')) {
            body.style.overflow = 'hidden';
            burger.innerHTML = '<i class="fas fa-times"></i>';
        } else {
            closeMenu();
        }
    };

    burger.addEventListener('click', toggleMobileMenu);
    overlay.addEventListener('click', closeMenu);

    navLinks.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) closeMenu();
        });
    });

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            const url = loginBtn.getAttribute('data-login-url');
            window.location.href = url && url.trim() !== '' ? url.trim() : '#';
        });
    }

    const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    if (isTouch && langBtn && langMenu && langDropdown) {
        langBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            langMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!langDropdown.contains(e.target)) {
                langMenu.classList.remove('show');
            }
        });
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth > 950) closeMenu();
        if (window.innerWidth <= 950 && langMenu) langMenu.classList.remove('show');
    });

    const updateScrolledState = () => {
        if (!navbar) return;
        navbar.classList.toggle('is-scrolled', window.scrollY > 16);
    };

    updateScrolledState();
    window.addEventListener('scroll', updateScrolledState, { passive: true });
}

async function initHeroSlider() {
    const target = document.querySelector('.webHeroSwiper');
    if (!target) return;

    loadStylesheetOnce('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    await loadScriptOnce('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js');

    if (typeof window.Swiper === 'undefined') return;

    const swiper = new window.Swiper(target, {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        speed: 1000,
        effect: 'slide',
        grabCursor: true,
        navigation: {
            nextEl: '.web-swiper-next',
            prevEl: '.web-swiper-prev',
        },
        pagination: {
            el: '.web-swiper-pagination',
            clickable: true,
        },
        keyboard: {
            enabled: true,
        },
    });

    const hero = document.querySelector('.web-hero-slider');
    if (!hero) return;
    hero.addEventListener('mouseenter', () => swiper.autoplay.stop());
    hero.addEventListener('mouseleave', () => swiper.autoplay.start());
}

function initCategoryCarousel() {
    function itemsPerView() {
        if (window.innerWidth <= 600) return 1;
        if (window.innerWidth <= 900) return 2;
        if (window.innerWidth <= 1200) return 3;
        return 4;
    }

    function initOne(root) {
        const track = root.querySelector('[data-category-track]');
        if (!track) return;

        const cards = Array.from(track.children);
        if (!cards.length) return;

        const prev = root.querySelector('[data-category-prev]');
        const next = root.querySelector('[data-category-next]');
        const dotsWrap = root.querySelector('[data-category-dots]');
        const direction = document.documentElement.dir === 'rtl' ? 1 : -1;

        let perView = itemsPerView();
        let page = 0;

        function totalPages() {
            return Math.max(1, Math.ceil(cards.length / perView));
        }

        function cardStep() {
            if (!cards[0]) return 0;
            const style = window.getComputedStyle(track);
            const gap = parseFloat(style.columnGap || style.gap || '24') || 24;
            return cards[0].offsetWidth + gap;
        }

        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            for (let i = 0; i < totalPages(); i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'shop-event-categories-dot';
                dot.setAttribute('data-active', i === page ? 'true' : 'false');
                dot.setAttribute('aria-label', `go-to-${i + 1}`);
                dot.addEventListener('click', () => go(i));
                dotsWrap.appendChild(dot);
            }
        }

        function syncDots() {
            if (!dotsWrap) return;
            dotsWrap.querySelectorAll('.shop-event-categories-dot').forEach((dot, i) => {
                dot.setAttribute('data-active', i === page ? 'true' : 'false');
            });
        }

        function go(nextPage) {
            const max = totalPages() - 1;
            page = Math.max(0, Math.min(nextPage, max));
            const offset = page * cardStep() * perView;
            track.style.transform = `translateX(${direction * offset}px)`;
            syncDots();
        }

        if (prev) prev.addEventListener('click', () => go(page - 1));
        if (next) next.addEventListener('click', () => go(page + 1));

        let rt;
        window.addEventListener('resize', () => {
            window.clearTimeout(rt);
            rt = window.setTimeout(() => {
                perView = itemsPerView();
                page = 0;
                buildDots();
                go(0);
            }, 180);
        });

        buildDots();
        go(0);
    }

    document.querySelectorAll('[data-event-category-carousel]').forEach(initOne);
}

function showWebFooterToast(message, isError) {
    const text = (message || '').trim();
    if (!text) return;
    let el = document.getElementById('web-footer-toast');
    if (!el) {
        el = document.createElement('div');
        el.id = 'web-footer-toast';
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        document.body.appendChild(el);
    }
    el.textContent = text;
    el.classList.toggle('web-footer-toast--error', Boolean(isError));
    el.classList.add('web-footer-toast--visible');
    window.clearTimeout(el._webFooterToastT);
    el._webFooterToastT = window.setTimeout(() => {
        el.classList.remove('web-footer-toast--visible');
    }, 3200);
}

function initPortalFooter() {
    function initOne(root) {
        if (!root || !root.id) return;
        const rootId = root.id;

        root.querySelectorAll('a[href="#"]').forEach((a) => {
            a.addEventListener('click', (e) => e.preventDefault());
        });

        const demoBtn = root.querySelector('.web-hajj-nl-demo');
        if (demoBtn) {
            demoBtn.addEventListener('click', () => {
                const inp = document.getElementById(`${rootId}-nl-email`);
                const okMsg = demoBtn.getAttribute('data-msg') || '';
                const badMsg = demoBtn.getAttribute('data-msg-invalid') || '';
                if (inp && inp.value && inp.value.includes('@')) {
                    showWebFooterToast(okMsg, false);
                    inp.value = '';
                } else {
                    showWebFooterToast(badMsg, true);
                    if (inp) inp.focus();
                }
            });
        }

        const backBtn = root.querySelector('[data-web-hajj-back-top]');
        if (backBtn) {
            const syncBackTop = () => {
                const y = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0;
                backBtn.classList.toggle('web-hajj-footer__back-top--visible', y > 300);
            };
            syncBackTop();
            window.addEventListener('scroll', syncBackTop, { passive: true });
            backBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }

    document.querySelectorAll('[data-web-portal-footer="1"]').forEach(initOne);
}

function initUniqueFooter() {
    function initOne(root) {
        if (!root) return;

        root.querySelectorAll('a[href="#"]').forEach((a) => {
            a.addEventListener('click', (e) => e.preventDefault());
        });

        const nlBtn = root.querySelector('.web-uf-nl-trigger');
        if (nlBtn) {
            nlBtn.addEventListener('click', () => {
                const inp = root.querySelector('.web-uf__nl-input');
                const email = (inp?.value || '').trim();
                const okMsg = nlBtn.getAttribute('data-msg-ok') || '';
                const badMsg = nlBtn.getAttribute('data-msg-bad') || '';
                const valid = email && email.includes('@') && email.includes('.');
                if (valid) {
                    showWebFooterToast(okMsg, false);
                    if (inp) inp.value = '';
                } else {
                    showWebFooterToast(badMsg, true);
                    if (inp) inp.focus();
                }
            });
        }

        const goTop = root.querySelector('[data-web-uf-gotop]');
        if (goTop) {
            const sync = () => {
                const y = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0;
                goTop.classList.toggle('web-uf__gotop--visible', y > 300);
            };
            sync();
            window.addEventListener('scroll', sync, { passive: true });
            goTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }

    document.querySelectorAll('[data-web-uf-footer="1"]').forEach(initOne);
}

function initStudentAccountDropdown() {
    const selector = '[data-role="student-account-dropdown"]';
    const closeAll = (target) => {
        document.querySelectorAll(selector).forEach((details) => {
            if (details.contains(target)) return;
            if (details.hasAttribute('open')) details.removeAttribute('open');
        });
    };

    document.addEventListener('pointerdown', (event) => {
        const el = event.target;
        if (!el) return;
        closeAll(el);
    }, { passive: true });
}

function initImageCarouselAutoplay() {
    function initTrack(track) {
        const slides = track.querySelectorAll('[data-carousel-slide]');
        if (!slides.length || slides.length < 2) return;

        let region = track.closest('[data-shop-image-carousel]');
        if (!region) region = track.parentElement;

        let reduceMotion = false;
        try { reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches; } catch (e) {}

        let intervalMs = parseInt(track.getAttribute('data-interval') || '5500', 10);
        if (isNaN(intervalMs) || intervalMs < 2500) intervalMs = 5500;

        let index = 0;
        let timer = null;
        let paused = false;
        const dots = region.querySelectorAll('[data-carousel-dot]');
        let dotsRaf = null;

        function updateDots(i) {
            if (!dots.length) return;
            const n = Math.max(0, Math.min(i, dots.length - 1));
            for (let j = 0; j < dots.length; j++) {
                const on = j === n;
                dots[j].setAttribute('data-active', on ? 'true' : 'false');
                dots[j].setAttribute('aria-current', on ? 'true' : 'false');
            }
        }

        function nearestSlideIndex() {
            const rect = track.getBoundingClientRect();
            const center = rect.left + rect.width / 2;
            let best = 0;
            let bestDist = Infinity;
            for (let i = 0; i < slides.length; i++) {
                const r = slides[i].getBoundingClientRect();
                const c = r.left + r.width / 2;
                const d = Math.abs(c - center);
                if (d < bestDist) { bestDist = d; best = i; }
            }
            return best;
        }

        function goTo(i) {
            const el = slides[i];
            if (!el || typeof el.scrollIntoView !== 'function') return;
            const behavior = reduceMotion ? 'auto' : 'smooth';
            el.scrollIntoView({ behavior, block: 'nearest', inline: 'start' });
            updateDots(i);
        }

        function tick() { index = (index + 1) % slides.length; goTo(index); }
        function stopTimer() { if (timer !== null) { window.clearInterval(timer); timer = null; } }
        function start() { if (reduceMotion || paused) return; stopTimer(); timer = window.setInterval(tick, intervalMs); }
        function pause() { paused = true; stopTimer(); }
        function resume() { paused = false; start(); }
        function restartAutoplayIfNeeded() { stopTimer(); if (!reduceMotion && !paused) start(); }
        function step(delta) { index = nearestSlideIndex(); index = (index + delta + slides.length) % slides.length; goTo(index); restartAutoplayIfNeeded(); }

        const prevBtn = region.querySelector('[data-carousel-prev]');
        const nextBtn = region.querySelector('[data-carousel-next]');
        if (prevBtn) prevBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); step(-1); });
        if (nextBtn) nextBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); step(1); });

        dots.forEach((dot) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                const j = parseInt(dot.getAttribute('data-carousel-dot-index') || '-1', 10);
                if (isNaN(j) || j < 0 || j >= slides.length) return;
                index = j;
                goTo(index);
                restartAutoplayIfNeeded();
            });
        });

        region.addEventListener('keydown', (e) => {
            if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
            if (!region.contains(e.target)) return;
            let rtl = false;
            try { rtl = window.getComputedStyle(track).direction === 'rtl'; } catch (err) {}
            const prevKey = rtl ? 'ArrowRight' : 'ArrowLeft';
            const nextKey = rtl ? 'ArrowLeft' : 'ArrowRight';
            if (e.key === prevKey) { e.preventDefault(); step(-1); }
            else if (e.key === nextKey) { e.preventDefault(); step(1); }
        });

        track.addEventListener('scroll', () => {
            if (dotsRaf !== null) window.cancelAnimationFrame(dotsRaf);
            dotsRaf = window.requestAnimationFrame(() => {
                dotsRaf = null;
                updateDots(nearestSlideIndex());
            });
            stopTimer();
            window.clearTimeout(track._carouselScrollEndT);
            track._carouselScrollEndT = window.setTimeout(() => {
                index = nearestSlideIndex();
                updateDots(index);
                if (!paused) start();
            }, 180);
        }, { passive: true });

        region.addEventListener('mouseenter', pause);
        region.addEventListener('mouseleave', resume);
        region.addEventListener('focusin', pause);
        region.addEventListener('focusout', (e) => { if (!region.contains(e.relatedTarget)) resume(); });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopTimer();
            else if (!paused) start();
        });

        updateDots(0);
        if (!reduceMotion) start();
    }

    document.querySelectorAll('[data-shop-image-carousel-track]').forEach(initTrack);
}

function initFlashMessages() {
    document.querySelectorAll('[data-shop-flash-dismiss]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const root = btn.closest('[data-shop-flash]');
            if (root) root.remove();
        });
    });

    document.querySelectorAll('[data-shop-flash]').forEach((el) => {
        const ms = parseInt(el.getAttribute('data-auto-dismiss') || '5000', 10);
        if (ms > 0) {
            window.setTimeout(() => {
                el.remove();
            }, ms);
        }
    });
}

function bootWebPackageUi() {
    initNavbar();
    initCategoryCarousel();
    initPortalFooter();
    initUniqueFooter();
    initStudentAccountDropdown();
    initImageCarouselAutoplay();
    initFlashMessages();
    initHeroSlider();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootWebPackageUi);
} else {
    bootWebPackageUi();
}
