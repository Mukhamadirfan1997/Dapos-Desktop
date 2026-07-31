@extends('dapos.layouts.app')

@section('title', 'Daftar Siswa per Rombel')

@section('content')
@php
    $allRombel = collect($rombelList)->flatten(1);
    $totalSiswa = $allRombel->sum('anggota_count');
    $totalRombel = $allRombel->count();
    $totalL = $allRombel->sum(fn($r) => $r->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'L')->count());
    $totalP = $allRombel->sum(fn($r) => $r->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'P')->count());
    $colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#20c997', '#0dcaf0', '#d63384'];
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h5 class="mb-0">Daftar Siswa per Rombel</h5>
        <span class="text-muted small">Ringkasan anggota rombongan belajar berdasarkan tingkat &amp; kelas</span>
    </div>
    <a href="{{ route('dapos.export.siswa-per-rombel-excel') }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export Excel
    </a>
</div>

@if ($rombelList->isEmpty())
<div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> Belum ada data anggota rombel. Silakan import dari Dapodik terlebih dahulu.
</div>
@else
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="rounded-3 text-bg-primary d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold lh-1">{{ $totalSiswa }}</div>
                    <div class="text-muted small">Total Siswa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="rounded-3 text-bg-info d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold lh-1">{{ $totalRombel }}</div>
                    <div class="text-muted small">Jumlah Rombel</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="rounded-3 text-bg-primary d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
                    <i class="bi bi-gender-male"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold lh-1">{{ $totalL }}</div>
                    <div class="text-muted small">Laki-laki</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="rounded-3 text-bg-danger d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;">
                    <i class="bi bi-gender-female"></i>
                </div>
                <div>
                    <div class="fs-5 fw-bold lh-1">{{ $totalP }}</div>
                    <div class="text-muted small">Perempuan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-3">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" id="filterSiswa" class="form-control" placeholder="Cari NISN / nama / ayah / sekolah asal..." autocomplete="off">
            <button class="btn btn-outline-secondary" type="button" id="resetFilter"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</div>

<ul class="nav nav-pills mb-3 flex-nowrap overflow-auto" id="tingkatTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-semua" type="button" role="tab">
            Semua <span class="badge text-bg-secondary ms-1">{{ $totalSiswa }}</span>
        </button>
    </li>
    @foreach ($rombelList as $tingkat => $rombelGroup)
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-{{ Str::slug($tingkat) }}" type="button" role="tab">
            {{ $tingkat }} <span class="badge text-bg-secondary ms-1">{{ $rombelGroup->sum('anggota_count') }}</span>
        </button>
    </li>
    @endforeach
</ul>

<div class="tab-content" id="tingkatTabContent">
    <div class="tab-pane fade show active" id="pane-semua" role="tabpanel">
        @foreach ($rombelList as $tingkat => $rombelGroup)
            @foreach ($rombelGroup as $rombel)
                @include('dapos.rombel._rombel_card', ['colors' => $colors, 'cardIndex' => $loop->iteration])
            @endforeach
        @endforeach
    </div>

    @foreach ($rombelList as $tingkat => $rombelGroup)
    <div class="tab-pane fade" id="pane-{{ Str::slug($tingkat) }}" role="tabpanel">
        @foreach ($rombelGroup as $rombel)
            @include('dapos.rombel._rombel_card', ['colors' => $colors, 'cardIndex' => $loop->iteration])
        @endforeach
    </div>
    @endforeach
</div>
@endif
@endsection

@push('scripts')
<script>
    window.addEventListener('load', function () {
        var PAGE_SIZE = 15;
        var tables = Array.prototype.slice.call(document.querySelectorAll('.daftar-siswa-table'));

        function pageTable(table, page) {
            var rows = Array.prototype.filter.call(table.querySelectorAll('tbody tr'), function (tr) {
                return tr.dataset.filtered !== '1';
            });
            var totalPages = Math.max(1, Math.ceil(rows.length / PAGE_SIZE));
            page = Math.min(Math.max(1, page), totalPages);
            table.dataset.page = String(page);

            Array.prototype.forEach.call(table.querySelectorAll('tbody tr'), function (tr) {
                tr.style.display = 'none';
            });
            var start = (page - 1) * PAGE_SIZE;
            rows.slice(start, start + PAGE_SIZE).forEach(function (tr) {
                tr.style.display = '';
            });

            var footer = table.parentNode.querySelector('.table-pagination');
            if (!footer) return;
            var startNo = rows.length ? (start + 1) : 0;
            var endNo = Math.min(start + PAGE_SIZE, rows.length);
            footer.querySelector('.pagination-info').textContent = rows.length
                ? 'Menampilkan ' + startNo + '-' + endNo + ' dari ' + rows.length + ' data'
                : 'Tidak ada data';
            footer.querySelector('[data-nav="prev"]').disabled = page <= 1;
            footer.querySelector('[data-nav="next"]').disabled = page >= totalPages;
        }

        tables.forEach(function (table) {
            table.dataset.page = '1';
            var footer = document.createElement('div');
            footer.className = 'd-flex justify-content-between align-items-center px-3 py-2 border-top small table-pagination';
            footer.innerHTML = '<span class="pagination-info text-muted"></span>' +
                '<div class="btn-group btn-group-sm">' +
                '<button type="button" class="btn btn-outline-secondary" data-nav="prev"><i class="bi bi-chevron-left"></i> Prev</button>' +
                '<button type="button" class="btn btn-outline-secondary" data-nav="next">Next <i class="bi bi-chevron-right"></i></button>' +
                '</div>';
            table.parentNode.appendChild(footer);
            footer.querySelector('[data-nav="prev"]').addEventListener('click', function () {
                pageTable(table, parseInt(table.dataset.page, 10) - 1);
            });
            footer.querySelector('[data-nav="next"]').addEventListener('click', function () {
                pageTable(table, parseInt(table.dataset.page, 10) + 1);
            });
            pageTable(table, 1);
        });

        var input = document.getElementById('filterSiswa');
        var reset = document.getElementById('resetFilter');
        if (!input) return;

        function applyFilter() {
            var q = input.value.trim().toLowerCase();
            document.querySelectorAll('.daftar-siswa-table tbody tr').forEach(function (tr) {
                var text = tr.textContent.toLowerCase();
                tr.dataset.filtered = (!q || text.indexOf(q) !== -1) ? '' : '1';
            });
            tables.forEach(function (table) { pageTable(table, 1); });
        }

        input.addEventListener('input', applyFilter);
        reset.addEventListener('click', function () {
            input.value = '';
            applyFilter();
            input.focus();
        });
    });
</script>
@endpush
