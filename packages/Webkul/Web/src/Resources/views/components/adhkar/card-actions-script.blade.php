@pushOnce('scripts', 'web-adhkar-card-actions')
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
                    favBtn.classList.toggle('is-saved');
                    webAdhkarToast(favBtn.classList.contains('is-saved')
                        ? @json(__('web::app.adhkar.toast_fav_added'))
                        : @json(__('web::app.adhkar.toast_fav_removed')));
                }
            });
        })();
    </script>
@endPushOnce
