<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pembaruan Aplikasi (GitHub Releases)
    |--------------------------------------------------------------------------
    |
    | Sumber pembaruan memakai GitHub Releases API. Tag rilis memakai format
    | "v{version}" (contoh: v1.0.0). Cek dilakukan dari sisi Electron
    | (electron/main.js) lewat IPC "check-update".
    |
    */

    'enabled' => env('UPDATER_ENABLED', true),

    // Owner/Repo di GitHub (format: owner/repo)
    'repo' => env('UPDATER_GITHUB_REPO', 'Mukhamadirfan1997/Dapos-Desktop'),

    // Versi lokal, dipakai sebagai pembanding terhadap rilis terbaru
    'version' => config('app.version'),
];
