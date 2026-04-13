@pushOnce('scripts', 'web-adhkar-card-actions')
    @auth('hajj')
        @php
            $favIds = auth()->guard('hajj')->user()->favoriteDuas()->pluck('manasik_duas.id')->all();
        @endphp
        <script>
            window.__hajjFavConfig = {
                toggleUrl: @json(route('hajj.account.favorites.toggle')),
                csrf: @json(csrf_token()),
                favoriteIds: @json(array_values(array_map('intval', $favIds))),
                toastAdded: @json(__('web::app.adhkar.toast_fav_added')),
                toastRemoved: @json(__('web::app.adhkar.toast_fav_removed')),
                toastNeedLogin: @json(__('web::app.adhkar.toast_fav_need_login')),
                toastError: @json(__('web::app.adhkar.toast_fav_error')),
            };
        </script>
    @else
        <script>
            window.__hajjFavConfig = null;
        </script>
    @endauth
    <script>
        (function () {
            function webAdhkarToast(msg) {
                var el = document.getElementById('web-adhkar-toast');
                if (! el) {
                    return;
                }
                el.textContent = msg;
                el.classList.add('show');
                clearTimeout(el._t);
                el._t = setTimeout(function () { el.classList.remove('show'); }, 2200);
            }

            function syncFavUiFromConfig() {
                var cfg = window.__hajjFavConfig;
                if (! cfg || ! Array.isArray(cfg.favoriteIds)) {
                    return;
                }
                document.querySelectorAll('.web-adhkar-card[data-dua-id]').forEach(function (card) {
                    var id = parseInt(card.getAttribute('data-dua-id') || '0', 10);
                    if (! id) {
                        return;
                    }
                    var btn = card.querySelector('[data-web-adhkar-fav]');
                    if (btn) {
                        btn.classList.toggle('is-saved', cfg.favoriteIds.indexOf(id) !== -1);
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncFavUiFromConfig);
            } else {
                syncFavUiFromConfig();
            }

            document.addEventListener('click', function (e) {
                var copyBtn = e.target.closest('[data-web-adhkar-copy]');
                if (copyBtn) {
                    var card = copyBtn.closest('.web-adhkar-card');
                    var textEl = card && card.querySelector('.web-adhkar-card__text');
                    var text = textEl ? textEl.innerText.trim() : '';
                    if (text && navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(function () {
                            webAdhkarToast(@json(__('web::app.adhkar.toast_copied')));
                        }).catch(function () { webAdhkarToast(@json(__('web::app.adhkar.toast_copy_failed'))); });
                    }
                    return;
                }
                var favBtn = e.target.closest('[data-web-adhkar-fav]');
                if (favBtn) {
                    var card = favBtn.closest('.web-adhkar-card');
                    var duaId = card ? parseInt(card.getAttribute('data-dua-id') || '0', 10) : 0;
                    var cfg = window.__hajjFavConfig;

                    if (! cfg || ! cfg.toggleUrl) {
                        webAdhkarToast(@json(__('web::app.adhkar.toast_fav_need_login')));
                        return;
                    }
                    if (! duaId) {
                        favBtn.classList.toggle('is-saved');
                        webAdhkarToast(favBtn.classList.contains('is-saved') ? cfg.toastAdded : cfg.toastRemoved);
                        return;
                    }

                    fetch(cfg.toggleUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': cfg.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ dua_id: duaId }),
                    }).then(function (res) {
                        if (! res.ok) {
                            throw new Error('bad status');
                        }
                        return res.json();
                    }).then(function (data) {
                        var saved = !! data.saved;
                        favBtn.classList.toggle('is-saved', saved);
                        var idx = cfg.favoriteIds.indexOf(duaId);
                        if (saved && idx === -1) {
                            cfg.favoriteIds.push(duaId);
                        }
                        if (! saved && idx !== -1) {
                            cfg.favoriteIds.splice(idx, 1);
                        }
                        webAdhkarToast(saved ? cfg.toastAdded : cfg.toastRemoved);
                    }).catch(function () {
                        webAdhkarToast(cfg.toastError);
                    });
                }
            });
        })();
    </script>
@endPushOnce
