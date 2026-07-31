<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-cloud-arrow-down me-1"></i> Cek Pembaruan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="updateModalBody"></div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    var api = window.daposApi;

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getModal() {
        if (!window.bootstrap) return null;
        return window.bootstrap.Modal.getOrCreateInstance(document.getElementById('updateModal'));
    }

    function setBody(html) {
        var el = document.getElementById('updateModalBody');
        if (el) el.innerHTML = html;
    }

    function showChecking() {
        setBody('<div class="text-center py-3"><div class="spinner-border text-primary mb-3" role="status"></div>' +
            '<div>Memeriksa pembaruan...</div></div>');
        var m = getModal();
        if (m) m.show();
    }

    function renderResult(r) {
        if (!r) r = { status: 'error', message: 'Tidak ada respon' };
        if (r.status === 'update') {
            setBody(
                '<div class="text-center mb-3"><i class="bi bi-download fs-1 text-primary"></i></div>' +
                '<h6 class="text-center mb-2">Versi baru tersedia: v' + escapeHtml(r.version || r.tag || '') + '</h6>' +
                (r.notes ? '<div class="small text-muted border-top pt-2 mt-2" style="white-space:pre-wrap;max-height:200px;overflow:auto;">' + escapeHtml(r.notes) + '</div>' : '') +
                '<div class="d-flex gap-2 mt-3"><button type="button" class="btn btn-primary flex-fill" id="updateDownloadBtn"><i class="bi bi-box-arrow-up-right me-1"></i> Download</button>' +
                '<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Nanti</button></div>'
            );
            var dl = document.getElementById('updateDownloadBtn');
            if (dl) dl.addEventListener('click', function () {
                if (api) api.openExternal(r.url || 'https://github.com/' + '{{ config('updater.repo') }}' + '/releases/latest');
            });
        } else if (r.status === 'latest') {
            setBody(
                '<div class="text-center py-2"><i class="bi bi-check-circle text-success fs-1"></i></div>' +
                '<h6 class="text-center mb-0">Anda sudah menggunakan versi terbaru.</h6>' +
                '<p class="text-center text-muted small mt-2 mb-0">' + (r.version ? 'Versi terpasang: v' + escapeHtml(r.version) : '') + '</p>'
            );
        } else {
            setBody(
                '<div class="text-center py-2"><i class="bi bi-wifi-off text-warning fs-1"></i></div>' +
                '<h6 class="text-center mb-2">Tidak dapat memeriksa pembaruan.</h6>' +
                '<p class="text-center text-muted small mb-0">Periksa koneksi internet Anda, lalu coba lagi.' +
                (r.message ? '<br><span class="text-muted">' + escapeHtml(r.message) + '</span>' : '') + '</p>'
            );
        }
    }

    function runCheck() {
        if (!api) return;
        showChecking();
        api.checkUpdate().then(function (r) {
            renderResult(r);
        }).catch(function (e) {
            renderResult({ status: 'error', message: e.message || '' });
        });
    }

    window.addEventListener('load', function () {
        var btns = document.querySelectorAll('.check-update-btn');
        btns.forEach(function (b) {
            if (!api) {
                b.disabled = true;
                b.title = 'Hanya tersedia di aplikasi desktop';
                return;
            }
            b.addEventListener('click', runCheck);
        });

        if (api) {
            api.onUpdateAvailable(function (r) {
                renderResult(r);
                var m = getModal();
                if (m) m.show();
            });
        }
    });
})();
</script>
