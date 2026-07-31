(function () {
    'use strict';

    var TIPS = [
        'Menghubungi server Dapodik lokal...',
        'Mengambil data dari Dapodik...',
        'Mencocokkan NISN dengan database lokal...',
        'Menyimpan data ke database SQLite...',
        'Memperbarui data periodik siswa...',
        'Merapikan data rombongan belajar...',
        'Menunggu respon Dapodik...',
        'Hampir selesai, mohon jangan menutup jendela ini...',
    ];

    var modalEl = null;
    var iconEl = null;
    var titleEl = null;
    var statusEl = null;
    var barEl = null;
    var percentEl = null;
    var logEl = null;
    var tipsTextEl = null;
    var tipTimer = null;

    function getMeta(name) {
        var m = document.querySelector('meta[name="' + name + '"]');
        return m ? m.getAttribute('content') : '';
    }

    function ensureModal() {
        if (modalEl) return;
        modalEl = document.getElementById('progressModal');
        iconEl = document.getElementById('progressIcon');
        titleEl = document.getElementById('progressTitle');
        statusEl = document.getElementById('progressStatus');
        barEl = document.getElementById('progressBar');
        percentEl = document.getElementById('progressPercent');
        logEl = document.getElementById('progressLog');
        tipsTextEl = document.getElementById('progressTipText');
    }

    function showModal() {
        ensureModal();
        var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
        startTips();
    }

    function stopTips() {
        if (tipTimer) { window.clearInterval(tipTimer); tipTimer = null; }
    }

    function startTips() {
        stopTips();
        var i = 0;
        if (tipsTextEl) tipsTextEl.textContent = TIPS[0];
        tipTimer = window.setInterval(function () {
            i = (i + 1) % TIPS.length;
            if (tipsTextEl) tipsTextEl.textContent = TIPS[i];
        }, 2500);
    }

    function setMode(mode) {
        ensureModal();
        if (iconEl) {
            var i = iconEl.querySelector('i');
            if (i) i.className = 'bi ' + (mode === 'import' ? 'bi-cloud-download' : 'bi-cloud-arrow-up');
        }
    }

    function setProgress(percent, status) {
        ensureModal();
        var p = Math.max(0, Math.min(100, Math.round(percent)));
        if (barEl) {
            barEl.style.width = p + '%';
            barEl.classList.remove('bg-success', 'bg-danger');
            barEl.classList.add('bg-primary');
        }
        if (percentEl) percentEl.textContent = p + '%';
        if (status && statusEl) statusEl.textContent = status;
    }

    function setTitle(text) {
        ensureModal();
        if (titleEl) titleEl.textContent = text;
    }

    function clearLog() {
        ensureModal();
        if (logEl) {
            logEl.innerHTML = '';
            logEl.style.display = 'none';
        }
    }

    function addLog(text, ok) {
        ensureModal();
        if (!logEl) return;
        logEl.style.display = '';
        var row = document.createElement('div');
        row.className = 'mb-1';
        var icon = ok === false
            ? '<span class="text-danger"><i class="bi bi-x-circle me-1"></i></span>'
            : '<span class="text-success"><i class="bi bi-check-circle me-1"></i></span>';
        row.innerHTML = icon + '<span>' + String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</span>';
        logEl.appendChild(row);
        logEl.scrollTop = logEl.scrollHeight;
    }

    function setSummary(iconClass, colorClass, title, subtitle) {
        ensureModal();
        if (iconEl) {
            iconEl.className = 'progress-icon ' + colorClass + ' mb-3';
            var i = iconEl.querySelector('i');
            if (i) i.className = 'bi ' + iconClass;
        }
        if (barEl) {
            barEl.style.width = '100%';
            barEl.classList.remove('bg-primary', 'progress-bar-animated');
            barEl.classList.add(colorClass.indexOf('danger') !== -1 ? 'bg-danger' : colorClass.indexOf('warning') !== -1 ? 'bg-warning' : 'bg-success');
        }
        if (percentEl) percentEl.textContent = 'Selesai';
        if (titleEl) titleEl.textContent = title;
        if (statusEl) statusEl.textContent = subtitle || '';
        stopTips();
    }

    function post(url, data) {
        var fd = new FormData();
        Object.keys(data || {}).forEach(function (k) { fd.append(k, data[k]); });
        return window.fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getMeta('csrf-token'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: fd
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (json) {
                if (!res.ok && !json.error) json.error = 'HTTP ' + res.status;
                return json;
            });
        });
    }

    function runImportAll(importUrl) {
        var steps = [
            ['siswa', 'Mengimpor data Siswa + Periodik'],
            ['registrasi', 'Mengimpor data Registrasi'],
            ['rombel', 'Mengimpor data Rombel'],
            ['anggota_rombel', 'Mengimpor Anggota Rombel'],
            ['pembelajaran', 'Mengimpor Pembelajaran']
        ];
        showModal();
        setMode('import');
        setTitle('Import Data Dapodik');
        clearLog();
        var done = 0;
        var allOk = true;

        function step(i) {
            if (i >= steps.length) {
                if (allOk) {
                    setSummary('bi-check-circle', 'text-success', 'Import Selesai', 'Semua data berhasil diimpor. Memuat ulang...');
                } else {
                    setSummary('bi-exclamation-triangle', 'text-warning', 'Import Selesai (dengan catatan)', 'Beberapa langkah gagal — lihat log. Memuat ulang...');
                }
                window.setTimeout(function () { location.reload(); }, 2200);
                return;
            }
            var label = steps[i][1];
            setTitle(label + ' (' + (i + 1) + '/' + steps.length + ')');
            setProgress((i / steps.length) * 100, 'Mengirim permintaan ke Dapodik...');
            post(importUrl + '/' + steps[i][0]).then(function (r) {
                done++;
                setProgress((done / steps.length) * 100, 'Memproses hasil...');
                addLog(label + ' — ' + (r.message || 'selesai'), r.success !== false);
                if (r.success === false) allOk = false;
                step(i + 1);
            }).catch(function (e) {
                done++;
                allOk = false;
                addLog(label + ' — Gagal: ' + e.message, false);
                step(i + 1);
            });
        }
        step(0);
    }

    function runImportStep(importUrl, stepKey, label) {
        showModal();
        setMode('import');
        setTitle(label);
        clearLog();
        setProgress(30, 'Mengirim permintaan ke Dapodik...');
        post(importUrl + '/' + stepKey).then(function (r) {
            setProgress(100, 'Selesai');
            addLog(r.message || 'Selesai', r.success !== false);
            if (r.success === false) {
                setSummary('bi-x-circle', 'text-danger', 'Import Gagal', r.message || 'Terjadi kesalahan');
            } else {
                setSummary('bi-check-circle', 'text-success', 'Import Selesai', r.message || '');
            }
            window.setTimeout(function () { location.reload(); }, 2200);
        }).catch(function (e) {
            setProgress(100, 'Gagal');
            addLog('Gagal: ' + e.message, false);
            setSummary('bi-x-circle', 'text-danger', 'Import Gagal', e.message);
            window.setTimeout(function () { location.reload(); }, 2200);
        });
    }

    function runSync(batchUrl, types) {
        showModal();
        setMode('sync');
        setTitle('Sinkron ke Dapodik');
        clearLog();

        var grandTotal = types.reduce(function (s, t) { return s + (t.total || 0); }, 0) || 1;
        var grandDone = 0;
        var synced = 0;
        var failed = 0;

        function syncType(i) {
            if (i >= types.length) {
                var allOk = failed === 0;
                if (allOk) {
                    setSummary('bi-check-circle', 'text-success', 'Sinkron Selesai', synced + ' berhasil, ' + failed + ' gagal. Memuat ulang...');
                } else {
                    setSummary('bi-exclamation-triangle', 'text-warning', 'Sinkron Selesai', synced + ' berhasil, ' + failed + ' gagal. Memuat ulang...');
                }
                window.setTimeout(function () { location.reload(); }, 2500);
                return;
            }

            var t = types[i];
            if (!t.total) {
                addLog(t.label + ' — tidak ada data untuk disinkron');
                syncType(i + 1);
                return;
            }
            setTitle(t.label + ' (' + (i + 1) + '/' + types.length + ')');
            var offset = 0;

            function batch() {
                post(batchUrl, { type: t.type, offset: offset, limit: 10 }).then(function (r) {
                    grandDone += r.processed || 0;
                    synced += r.synced || 0;
                    failed += r.failed || 0;
                    setProgress((grandDone / grandTotal) * 100, 'Memproses ' + (offset + 1) + '-' + (offset + (r.processed || 0)) + ' dari ' + (r.total || t.total) + ' data...');
                    if (r.errors && r.errors.length) {
                        r.errors.forEach(function (e) { addLog(t.label + ' — ' + e, false); });
                    }
                    if (r.done) {
                        addLog(t.label + ' — ' + (r.synced || 0) + ' berhasil, ' + (r.failed || 0) + ' gagal');
                        syncType(i + 1);
                        return;
                    }
                    offset = r.next_offset || (offset + (r.processed || 0));
                    if (offset >= (r.total || 0)) {
                        addLog(t.label + ' — ' + (r.synced || 0) + ' berhasil, ' + (r.failed || 0) + ' gagal');
                        syncType(i + 1);
                        return;
                    }
                    batch();
                }).catch(function (e) {
                    failed++;
                    addLog(t.label + ' — Gagal: ' + e.message, false);
                    syncType(i + 1);
                });
            }
            batch();
        }
        syncType(0);
    }

    window.DaposRunner = {
        runImportAll: runImportAll,
        runImportStep: runImportStep,
        runSync: runSync
    };
})();
