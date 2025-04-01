<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateEvidenceFiles extends Command
{
    protected $signature = 'evidence:migrate-files';
    protected $description = 'Migrate evidence files from old to new location';

    public function handle()
    {
        $oldPath = storage_path('app/evidences');
        $newPath = storage_path('app/public/evidences');

        if (!file_exists($oldPath)) {
            $this->error('Old directory does not exist!');
            return;
        }

        if (!file_exists($newPath)) {
            mkdir($newPath, 0775, true);
        }

        $files = glob($oldPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $newFilePath = $newPath . '/' . $fileName;
            
            if (copy($file, $newFilePath)) {
                $this->info("Migrated: $fileName");
            } else {
                $this->error("Failed to migrate: $fileName");
            }
        }

        $this->info('Migration completed!');
    }
} 