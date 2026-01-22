<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('backup:database {--retention=}', function () {
    $connection = config('database.default');
    if ($connection !== 'sqlite') {
        $this->warn('Database backups are only supported for sqlite in this app.');
        return 0;
    }

    $databasePath = config('database.connections.sqlite.database');
    $isWindowsPath = preg_match('/^[A-Za-z]:\\\\/', $databasePath) === 1;
    if (!Str::startsWith($databasePath, DIRECTORY_SEPARATOR) && !$isWindowsPath) {
        $databasePath = base_path($databasePath);
    }

    if (!File::exists($databasePath)) {
        $this->error('SQLite database not found at: '.$databasePath);
        return 1;
    }

    $disk = env('BACKUP_DISK', 'local');
    $backupDir = trim(env('BACKUP_PATH', 'backups'), '/');
    $timestamp = now()->format('Ymd_His');
    $backupName = 'database_'.$timestamp.'.sqlite';

    Storage::disk($disk)->makeDirectory($backupDir);
    Storage::disk($disk)->put($backupDir.'/'.$backupName, File::get($databasePath));

    $retention = (int) ($this->option('retention') ?: env('BACKUP_RETENTION_DAYS', 7));
    if ($retention > 0) {
        $cutoff = now()->subDays($retention);
        foreach (Storage::disk($disk)->files($backupDir) as $file) {
            if (!Str::endsWith($file, '.sqlite')) {
                continue;
            }

            $lastModified = Storage::disk($disk)->lastModified($file);
            if ($lastModified && Carbon::createFromTimestamp($lastModified)->lt($cutoff)) {
                Storage::disk($disk)->delete($file);
            }
        }
    }

    $this->info('Backup created: '.$backupDir.'/'.$backupName);
})->purpose('Create a database backup (sqlite only).');

Schedule::command('backup:database')->dailyAt(env('BACKUP_TIME', '02:00'));
