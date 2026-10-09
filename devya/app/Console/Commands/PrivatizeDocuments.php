<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use App\Models\MedicalReport;
use App\Models\Patient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PrivatizeDocuments extends Command
{
    protected $signature = 'documents:privatize';

    protected $description = 'Move legacy confidential uploads to private storage after verifying file contents';

    public function handle(): int
    {
        $target = Storage::disk('confidential');
        $directories = ['patients/reports', 'patients/documents', 'doctors/documents', 'doctors/slmc-documents'];
        $moved = 0;

        foreach (['public', 'local'] as $disk) {
            $source = Storage::disk($disk);

            foreach ($directories as $directory) {
                foreach ($source->allFiles($directory) as $path) {
                    $this->assertStoragePath($source->path($path));
                    $this->assertStoragePath($target->path($path));
                    $sourceHash = hash_file('sha256', $source->path($path));

                    if (! $target->exists($path)) {
                        $stream = $source->readStream($path);

                        try {
                            $target->put($path, $stream);
                        } finally {
                            if (is_resource($stream)) {
                                fclose($stream);
                            }
                        }
                    }

                    if ($sourceHash !== hash_file('sha256', $target->path($path))) {
                        throw new RuntimeException('Conflicting private file found. The original file was not removed.');
                    }

                    if (! $source->delete($path)) {
                        throw new RuntimeException('Could not remove a verified legacy copy. Run the command again.');
                    }

                    $moved++;
                }
            }
        }

        $missing = 0;

        foreach ([MedicalReport::class => ['file_path'], Patient::class => ['nic_passport_copy'], Doctor::class => ['nic_copy', 'slmc_registration_document']] as $model => $columns) {
            $model::withoutGlobalScopes()->select(['id', ...$columns])->chunkById(100, function ($records) use ($target, $columns, &$missing): void {
                foreach ($records as $record) {
                    foreach ($columns as $column) {
                        if (filled($record->{$column}) && ! $target->exists($record->{$column})) {
                            $missing++;
                        }
                    }
                }
            });
        }

        $this->info("Verified and moved {$moved} files. Missing referenced files: {$missing}.");

        return $missing === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function assertStoragePath(string $path): void
    {
        $existing = $path;

        while (! file_exists($existing)) {
            $parent = dirname($existing);

            if ($parent === $existing) {
                throw new RuntimeException('Could not resolve document storage path.');
            }

            $existing = $parent;
        }

        $resolved = strtolower((string) realpath($existing));
        $root = strtolower((string) realpath(storage_path()));

        if ($resolved !== $root && ! str_starts_with($resolved, $root.DIRECTORY_SEPARATOR)) {
            throw new RuntimeException('Document migration may only modify files inside application storage.');
        }
    }
}
