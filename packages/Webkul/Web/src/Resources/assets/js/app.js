import '../css/app.css';

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

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

function parsePrayerMinutes(timeStr) {
    if (!timeStr || typeof timeStr !== 'string') return null;
    const parts = timeStr.trim().split(':');
    if (parts.length < 2) return null;
    const h = parseInt(parts[0], 10);
    const m = parseInt(parts[1], 10);
    if (Number.isNaN(h) || Number.isNaN(m)) return null;
    return h * 60 + m;
}

/** Raw HH:mm (24h) from API timings */
function prayerRawTime24(timings, key) {
    const t = timings && timings[key];
    if (typeof t !== 'string') return '';
    return t.split(' ')[0].trim() || '';
}

function escapeHtml(text) {
    if (text == null) return '';
    const div = document.createElement('div');
    div.textContent = String(text);
    return div.innerHTML;
}

/** LTR island for clock text (digits then ص/م) inside RTL <html dir="rtl"> */
function wrapPrayerClockHtml(innerSafe) {
    return `<span dir="ltr" class="web-prayer-times__clock" translate="no">${innerSafe}</span>`;
}

function formatPrayerTimeDisplayHtml(raw24, use12h, clock) {
    if (!raw24) return wrapPrayerClockHtml('--:--');
    const total = parsePrayerMinutes(raw24);
    if (total === null) return wrapPrayerClockHtml('--:--');
    const h24 = Math.floor(total / 60) % 24;
    const m = total % 60;
    const mm = String(m).padStart(2, '0');
    if (!use12h) {
        return wrapPrayerClockHtml(`${String(h24).padStart(2, '0')}:${mm}`);
    }
    const h12 = h24 % 12 || 12;
    const am = (clock && clock.am) || 'AM';
    const pm = (clock && clock.pm) || 'PM';
    const suffix = h24 < 12 ? am : pm;
    return wrapPrayerClockHtml(`${h12}:${mm}\u202f${escapeHtml(suffix)}`);
}

function readPrayerTimesConfig(root) {
    const scriptEl = root.querySelector('script.web-prayer-times__config[type="application/json"]');
    if (scriptEl && scriptEl.textContent.trim() !== '') {
        try {
            return JSON.parse(scriptEl.textContent);
        } catch {
            /* fall through */
        }
    }
    try {
        return JSON.parse(root.getAttribute('data-prayer-config') || '{}');
    } catch {
        return {};
    }
}

function extractAladhanPayload(data) {
    if (!data || typeof data !== 'object') {
        return null;
    }
    const inner = data.data && typeof data.data === 'object' ? data.data : data;
    const timings = inner.timings && typeof inner.timings === 'object' ? inner.timings : null;
    if (!timings) {
        return null;
    }
    const ok = data.code === undefined || data.code === 200;
    if (!ok) {
        return null;
    }
    return {
        timings,
        date: inner.date && typeof inner.date === 'object' ? inner.date : null,
    };
}

async function initPrayerTimesRoot(root) {
    if (root.dataset.prayerInited === '1') return;
    const cfg = readPrayerTimesConfig(root);
    if (!cfg || typeof cfg !== 'object') {
        return;
    }
    const city = cfg.city || 'Makkah';
    const country = cfg.country || 'Saudi Arabia';
    const method = Number.isFinite(cfg.method) ? cfg.method : 2;
    const autoplayMs = Number.isFinite(cfg.autoplayMs) ? cfg.autoplayMs : 4000;
    const prayerLabels = (cfg.labels && cfg.labels.prayers) || {};
    const clockLabels = (cfg.labels && cfg.labels.clock) || {};
    const customApiUrl = typeof cfg.apiUrl === 'string' ? cfg.apiUrl.trim() : '';
    const use12h = cfg.hour12 !== false;

    async function applyTimings() {
        let url = customApiUrl;
        if (!url) {
            const params = new URLSearchParams({
                city,
                country,
                method: String(method),
            });
            url = `https://api.aladhan.com/v1/timingsByCity?${params.toString()}`;
        }
        try {
            const response = await fetch(url, { credentials: 'omit' });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            const data = await response.json();
            const payload = extractAladhanPayload(data);
            if (!payload) {
                throw new Error('bad response');
            }
            const { timings } = payload;
            const { date } = payload;

            root.querySelectorAll('[data-prayer-time]').forEach((el) => {
                const key = el.getAttribute('data-prayer-time');
                if (!key) return;
                const raw = prayerRawTime24(timings, key);
                el.innerHTML = formatPrayerTimeDisplayHtml(raw, use12h, clockLabels);
            });

            const hijriEl = root.querySelector('[data-prayer-hijri]');
            const gregEl = root.querySelector('[data-prayer-gregorian]');
            if (date && hijriEl && gregEl) {
                const h = date.hijri || {};
                const g = date.gregorian || {};
                const hijriLine = `${h.weekday?.ar || h.weekday?.en || ''} - ${h.day || ''} ${h.month?.ar || h.month?.en || ''} ${h.year || ''}`.trim();
                hijriEl.innerHTML = `<i class="fas fa-moon" aria-hidden="true"></i> ${hijriLine}`;
                gregEl.innerHTML = `<i class="fas fa-calendar-alt" aria-hidden="true"></i> ${g.date || ''}`;
            }

            const now = new Date();
            const currentM = now.getHours() * 60 + now.getMinutes();
            const nextKeys = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
            let nextName = '';
            let nextRaw = '';
            let minDiff = Infinity;
            nextKeys.forEach((key) => {
                const raw = prayerRawTime24(timings, key);
                const pm = parsePrayerMinutes(raw);
                if (pm === null) return;
                let diff = pm - currentM;
                if (diff <= 0) diff += 24 * 60;
                if (diff < minDiff) {
                    minDiff = diff;
                    nextName = prayerLabels[key] || key;
                    nextRaw = raw;
                }
            });

            const nameEl = root.querySelector('[data-prayer-next-name]');
            const timeEl = root.querySelector('[data-prayer-next-time]');
            if (nameEl) nameEl.textContent = nextName || '--';
            if (timeEl) {
                timeEl.innerHTML = nextRaw
                    ? formatPrayerTimeDisplayHtml(nextRaw, use12h, clockLabels)
                    : wrapPrayerClockHtml('--:--');
            }
        } catch {
            root.querySelectorAll('[data-prayer-time]').forEach((el) => {
                el.innerHTML = wrapPrayerClockHtml('--:--');
            });
            const nameEl = root.querySelector('[data-prayer-next-name]');
            const timeEl = root.querySelector('[data-prayer-next-time]');
            if (nameEl) nameEl.textContent = '--';
            if (timeEl) timeEl.innerHTML = wrapPrayerClockHtml('--:--');
        }
    }

    await applyTimings();
    window.setInterval(applyTimings, 60 * 60 * 1000);

    const swiperEl = root.querySelector('.web-prayer-times__swiper');
    if (swiperEl && swiperEl.dataset.swiperInited !== '1') {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const rtl = document.documentElement.getAttribute('dir') === 'rtl';
        try {
            // eslint-disable-next-line no-new
            new Swiper(swiperEl, {
                /* Two prayers per slide; autoplay + clickable pagination dots */
                slidesPerView: 1,
                spaceBetween: 18,
                centeredSlides: true,
                loop: true,
                autoplay: reduceMotion
                    ? false
                    : {
                          delay: autoplayMs,
                          disableOnInteraction: false,
                          pauseOnMouseEnter: true,
                      },
                pagination: {
                    el: root.querySelector('.web-prayer-times__pagination'),
                    type: 'bullets',
                    clickable: true,
                },
                speed: 500,
                rtl,
            });
        } catch (err) {
            console.warn('[web-prayer-times] Swiper init failed', err);
        }
        swiperEl.dataset.swiperInited = '1';
    }

    root.dataset.prayerInited = '1';
}

function initWebPrayerTimes() {
    document.querySelectorAll('[data-web-prayer-times]').forEach((root) => {
        initPrayerTimesRoot(root).catch((err) => console.warn('[web-prayer-times] init failed', err));
    });
}

function initNavbar() {
    const burger = document.getElementById('webBurgerBtn');
    const navLinks = document.getElementById('webNavLinks');
    const navCloseBtn = document.getElementById('webNavCloseBtn');
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
    if (navCloseBtn) {
        navCloseBtn.addEventListener('click', closeMenu);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && navLinks.classList.contains('active')) {
            closeMenu();
        }
    });

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
            const scrollTop = () => {
                const se = document.scrollingElement;
                return (
                    window.scrollY
                    || window.pageYOffset
                    || (se && se.scrollTop)
                    || document.documentElement.scrollTop
                    || document.body.scrollTop
                    || 0
                );
            };
            const sync = () => {
                const y = scrollTop();
                const docH = Math.max(
                    document.body?.scrollHeight || 0,
                    document.documentElement?.scrollHeight || 0,
                    0,
                );
                const vh = window.innerHeight || document.documentElement.clientHeight || 0;
                const canScroll = docH > vh + 40;
                goTop.classList.toggle(
                    'web-uf__gotop--visible',
                    canScroll && y > 100,
                );
            };
            sync();
            window.addEventListener('scroll', sync, { passive: true });
            window.addEventListener('resize', sync, { passive: true });
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

const SHOP_FLASH_STYLES = {
    success: {
        container: 'background: #D4EDDA',
        message: 'color: #155721',
        icon: 'color: #155721',
    },
    error: {
        container: 'background: #F8D7DA',
        message: 'color: #721C24',
        icon: 'color: #721C24',
    },
    warning: {
        container: 'background: #FFF3CD',
        message: 'color: #856404',
        icon: 'color: #856404',
    },
    info: {
        container: 'background: #E2E3E5',
        message: 'color: #383D41',
        icon: 'color: #383D41',
    },
};

const SHOP_FLASH_ICON_CLASS = {
    success: 'icon-toast-done',
    error: 'icon-toast-error',
    warning: 'icon-toast-exclamation-mark',
    info: 'icon-toast-info',
};

function webFlashCloseLabel() {
    return (typeof window.__webFlashCloseLabel === 'string' && window.__webFlashCloseLabel)
        ? window.__webFlashCloseLabel
        : 'Dismiss notification';
}

function scheduleShopFlashAutoDismiss(el) {
    const ms = parseInt(el.getAttribute('data-auto-dismiss') || '5000', 10);
    if (ms > 0) {
        window.setTimeout(() => {
            if (el.isConnected) {
                el.remove();
            }
        }, ms);
    }
}

function buildShopFlashElement(type, message) {
    const t = SHOP_FLASH_STYLES[type] || SHOP_FLASH_STYLES.info;
    const iconClass = SHOP_FLASH_ICON_CLASS[type] || SHOP_FLASH_ICON_CLASS.info;
    const msgHtml = escapeHtml(String(message ?? '')).replace(/\n/g, '<br>');

    const root = document.createElement('div');
    root.setAttribute('data-shop-flash', '');
    root.setAttribute('data-auto-dismiss', '5000');
    root.className =
        'web-flash-group-item flex w-max max-w-[408px] justify-between gap-12 rounded-lg px-5 py-3 max-sm:max-w-80 max-sm:items-center max-sm:gap-2 max-sm:p-3';
    root.style.cssText = t.container;

    root.innerHTML = `
        <p class="flex items-center break-words text-sm" style="${t.message}">
            <span class="${iconClass} text-2xl ltr:mr-2.5 rtl:ml-2.5" style="${t.icon}" aria-hidden="true"></span>
            <span>${msgHtml}</span>
        </p>
        <span role="button" tabindex="0" data-shop-flash-dismiss class="icon-cancel max-h-4 max-w-4 shrink-0 cursor-pointer" style="${t.icon}" aria-label=""></span>
    `;
    const dismiss = root.querySelector('[data-shop-flash-dismiss]');
    if (dismiss) {
        dismiss.setAttribute('aria-label', webFlashCloseLabel());
    }

    return root;
}

function appendShopFlashCopies(type, message) {
    const proto = buildShopFlashElement(type, message);
    const desktop = document.querySelector('[data-web-flash-desktop]');
    const mobile = document.querySelector('[data-web-flash-mobile]');
    if (desktop) {
        const node = proto.cloneNode(true);
        desktop.appendChild(node);
        scheduleShopFlashAutoDismiss(node);
    }
    if (mobile) {
        const node = proto.cloneNode(true);
        mobile.appendChild(node);
        scheduleShopFlashAutoDismiss(node);
    }
}

function initFlashMessages() {
    document.addEventListener('click', (e) => {
        const dismiss = e.target.closest('[data-shop-flash-dismiss]');
        if (!dismiss) {
            return;
        }
        const root = dismiss.closest('[data-shop-flash]');
        if (root) {
            root.remove();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter' && e.key !== ' ') {
            return;
        }
        const dismiss = e.target.closest('[data-shop-flash-dismiss]');
        if (!dismiss) {
            return;
        }
        e.preventDefault();
        const root = dismiss.closest('[data-shop-flash]');
        if (root) {
            root.remove();
        }
    });

    document.querySelectorAll('[data-shop-flash]').forEach(scheduleShopFlashAutoDismiss);

    window.Webkul = window.Webkul || {};
    window.Webkul.addFlash = (payload) => {
        const o = payload && typeof payload === 'object' ? payload : {};
        const type = o.type || 'info';
        const msg = o.message != null ? String(o.message) : '';
        appendShopFlashCopies(type, msg);
    };

    document.addEventListener('add-flash', (e) => {
        const d = e && e.detail && typeof e.detail === 'object' ? e.detail : {};
        window.Webkul.addFlash(d);
    });
}

function initHajjAccountPage() {
    const root = document.querySelector('.hajj-account-page');
    if (!root) {
        return;
    }

    const validationSummary = root.getAttribute('data-hajj-validation-summary');
    if (validationSummary && window.Webkul && typeof window.Webkul.addFlash === 'function') {
        window.Webkul.addFlash({ type: 'error', message: validationSummary });
    }

    root.querySelectorAll('.hajj-account-tab-btn[data-hajj-tab]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const tab = btn.getAttribute('data-hajj-tab');
            root.querySelectorAll('.hajj-account-tab-btn').forEach((b) => {
                const on = b === btn;
                b.classList.toggle('is-active', on);
                b.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            root.querySelectorAll('[data-hajj-tab-panel]').forEach((panel) => {
                const show = panel.getAttribute('data-hajj-tab-panel') === tab;
                panel.toggleAttribute('hidden', !show);
                panel.classList.toggle('is-active', show);
            });
        });
    });

    const burger = root.querySelector('[data-hajj-account-burger]');
    const mobileNav = root.querySelector('[data-hajj-account-mobile-nav]');
    if (burger && mobileNav) {
        burger.addEventListener('click', () => {
            const willOpen = mobileNav.hidden;
            mobileNav.hidden = !willOpen;
            burger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    }

    root.querySelectorAll('[data-hajj-account-avatar]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const msg = root.getAttribute('data-hajj-avatar-msg') || '';
            if (msg && window.Webkul && typeof window.Webkul.addFlash === 'function') {
                window.Webkul.addFlash({ type: 'info', message: msg });
            }
        });
    });

    const delForm = root.querySelector('form[data-hajj-delete-account]');
    if (delForm) {
        delForm.addEventListener('submit', (e) => {
            const c1 = root.getAttribute('data-hajj-delete-confirm') || '';
            const c2 = root.getAttribute('data-hajj-delete-final') || '';
            if (!window.confirm(c1)) {
                e.preventDefault();
                return;
            }
            if (!window.confirm(c2)) {
                e.preventDefault();
            }
        });
    }

    root.querySelectorAll('[data-toggle-pass]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle-pass');
            const input = id ? document.getElementById(id) : null;
            if (!input) {
                return;
            }
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.className = 'fas fa-eye-slash';
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.className = 'fas fa-eye';
                }
            }
        });
    });
}

function initWebModalConfirm() {
    const root = document.querySelector('[data-web-modal-confirm]');
    if (!root) {
        return;
    }

    const backdrop = root.querySelector('[data-wmc-backdrop]');
    const titleEl = root.querySelector('[data-wmc-title]');
    const messageEl = root.querySelector('[data-wmc-message]');
    const btnDisagree = root.querySelector('[data-wmc-disagree]');
    const btnAgree = root.querySelector('[data-wmc-agree]');

    if (!backdrop || !titleEl || !messageEl || !btnDisagree || !btnAgree) {
        return;
    }

    const readDefaults = () => ({
        title: root.getAttribute('data-default-title') || '',
        message: root.getAttribute('data-default-message') || '',
        btnDisagree: root.getAttribute('data-default-btn-disagree') || '',
        btnAgree: root.getAttribute('data-default-btn-agree') || '',
    });

    let agreeCb = () => {};
    let disagreeCb = () => {};

    function unlockBody() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function close() {
        root.classList.remove('web-modal-confirm--open');
        root.setAttribute('aria-hidden', 'true');
        unlockBody();
        document.removeEventListener('keydown', onKeyDown);
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') {
            disagree();
        }
    }

    function disagree() {
        const fn = disagreeCb;
        close();
        try {
            fn();
        } catch (err) {
            console.error(err);
        }
    }

    function agree() {
        const fn = agreeCb;
        close();
        try {
            fn();
        } catch (err) {
            console.error(err);
        }
    }

    function open(detail = {}) {
        const d = readDefaults();
        const opts = detail.options && typeof detail.options === 'object' ? detail.options : {};

        titleEl.textContent = detail.title != null ? String(detail.title) : d.title;
        messageEl.textContent = detail.message != null ? String(detail.message) : d.message;
        btnDisagree.textContent =
            opts.btnDisagree != null ? String(opts.btnDisagree) : d.btnDisagree;
        btnAgree.textContent = opts.btnAgree != null ? String(opts.btnAgree) : d.btnAgree;

        agreeCb = typeof detail.agree === 'function' ? detail.agree : () => {};
        disagreeCb = typeof detail.disagree === 'function' ? detail.disagree : () => {};

        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        if (scrollbarWidth > 0) {
            document.body.style.paddingRight = `${scrollbarWidth}px`;
        }

        root.classList.add('web-modal-confirm--open');
        root.setAttribute('aria-hidden', 'false');
        document.addEventListener('keydown', onKeyDown);
        requestAnimationFrame(() => {
            btnAgree.focus();
        });
    }

    btnDisagree.addEventListener('click', () => disagree());
    btnAgree.addEventListener('click', () => agree());
    backdrop.addEventListener('click', () => disagree());

    window.Webkul = window.Webkul || {};
    window.Webkul.WebModalConfirm = { open };

    document.addEventListener('open-confirm-modal', (e) => {
        open(e.detail || {});
    });
}

function initHajjAuthPage() {
    if (!document.body.classList.contains('hajj-auth-page')) {
        return;
    }
    const msgForgot = document.body.getAttribute('data-hajj-forgot-msg') || '';
    const msgSocial = document.body.getAttribute('data-hajj-social-msg') || '';

    document.querySelectorAll('[data-hajj-forgot]').forEach((btn) => {
        btn.addEventListener('click', () => {
            window.alert(msgForgot);
        });
    });
    document.querySelectorAll('[data-hajj-social]').forEach((btn) => {
        btn.addEventListener('click', () => {
            window.alert(msgSocial);
        });
    });
    document.querySelectorAll('[data-toggle-pass]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle-pass');
            const input = id ? document.getElementById(id) : null;
            if (!input) {
                return;
            }
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.className = 'fas fa-eye-slash';
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.className = 'fas fa-eye';
                }
            }
        });
    });
}

function bootWebPackageUi() {
    initWebModalConfirm();
    initNavbar();
    initCategoryCarousel();
    initPortalFooter();
    initUniqueFooter();
    initStudentAccountDropdown();
    initImageCarouselAutoplay();
    initFlashMessages();
    initHeroSlider();
    initWebPrayerTimes();
    initHajjAuthPage();
    initHajjAccountPage();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootWebPackageUi);
} else {
    bootWebPackageUi();
}
