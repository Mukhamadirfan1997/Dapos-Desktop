@if (!$disclaimerSeen)
<div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disclaimerModalLabel">
                    <i class="bi bi-shield-exclamation text-warning me-1"></i> Perhatian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Selamat datang di <strong>DAPOS Desktop</strong>. Aplikasi ini adalah perangkat resmi
                    untuk membantu tugas operator sekolah dalam mengelola data pokok sekolah.</p>
                <ul class="small mb-0 ps-3">
                    <li>Data yang tersimpan bersifat <strong>rahasia</strong> dan hanya untuk kepentingan sekolah.</li>
                    <li>Dilarang <strong>memperjualbelikan, memodifikasi, atau menyalahgunakan</strong> aplikasi ini.</li>
                    <li>Gunakan sesuai ketentuan dan tidak untuk mengakses data pihak lain tanpa izin.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="disclaimerAck">
                    <i class="bi bi-check-lg me-1"></i> Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('load', function () {
    if (!window.bootstrap) return;
    var modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('disclaimerModal'));
    modal.show();
    var btn = document.getElementById('disclaimerAck');
    if (btn) {
        btn.addEventListener('click', function () {
            var csrf = document.querySelector('meta[name="csrf-token"]');
            fetch('{{ route('dapos.disclaimer.ack') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (r) {
                modal.hide();
            }).catch(function () {
                modal.hide();
            });
        });
    }
});
</script>
@endif
