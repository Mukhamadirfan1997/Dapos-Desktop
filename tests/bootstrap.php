<?php

require __DIR__ . '/../vendor/autoload.php';

if (is_file(__DIR__ . '/../bootstrap/cache/config.php')) {
    fwrite(STDERR, PHP_EOL
        . '============================================================' . PHP_EOL
        . 'BERHENTI: bootstrap/cache/config.php aktif.' . PHP_EOL
        . 'Dengan config cache aktif, pengaturan DB_DATABASE=:memory: di' . PHP_EOL
        . 'phpunit.xml DIABAIKAN, sehingga RefreshDatabase (migrate:fresh)' . PHP_EOL
        . 'akan MENIMPA database produksi (database/database.sqlite).' . PHP_EOL
        . 'Jalankan "php artisan config:clear" sebelum "php artisan test".' . PHP_EOL
        . '============================================================' . PHP_EOL . PHP_EOL);
    exit(1);
}
