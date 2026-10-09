<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class StaffDocument extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Database table name.
     */
    protected $table = 'staff_documents';

    /**
     * Fields allowed for mass assignment.
     */
    protected $fillable = [
        'staff_id',
        'document_type',
        'title',
        'file_path',
        'original_file_name',
        'file_type',
        'file_size',
        'description',
        'document_date',
        'is_current',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'staff_id' => 'integer',
            'document_date' => 'date',
            'file_size' => 'integer',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Staff document belongs to one staff member.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Private storage disk name.
     */
    public function getStorageDiskName(): string
    {
        return 'staff_documents';
    }

    /**
     * Get the private storage disk.
     */
    public function storageDisk()
    {
        return Storage::disk($this->getStorageDiskName());
    }

    /**
     * Check whether the document file exists.
     */
    public function fileExists(): bool
    {
        if (empty($this->file_path)) {
            return false;
        }

        return $this->storageDisk()->exists($this->file_path);
    }

    /**
     * Get the file MIME type.
     */
    public function getFileMimeType(): ?string
    {
        if (! $this->fileExists()) {
            return null;
        }

        return $this->storageDisk()->mimeType($this->file_path);
    }

    /**
     * Get the file size in bytes.
     */
    public function getFileSizeInBytes(): ?int
    {
        if (! $this->fileExists()) {
            return null;
        }

        return $this->storageDisk()->size($this->file_path);
    }

    /**
     * Get a readable file size.
     */
    public function getReadableFileSize(): string
    {
        $bytes = $this->getFileSizeInBytes();

        if ($bytes === null) {
            return 'Unknown';
        }

        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return round($bytes / (1024 * 1024), 2).' MB';
        }

        return round($bytes / (1024 * 1024 * 1024), 2).' GB';
    }

    /**
     * Get the original filename.
     */
    public function getDisplayFileName(): string
    {
        return $this->original_file_name
            ?: basename($this->file_path);
    }

    /**
     * Automatically save file metadata before saving.
     *
     * The file is stored on the private "staff_documents" disk.
     */
    protected static function booted(): void
    {
        static::saving(function (StaffDocument $document): void {

            if (empty($document->file_path)) {
                return;
            }

            $disk = Storage::disk('staff_documents');

            /*
             * If the file does not exist, do not try to read metadata.
             */
            if (! $disk->exists($document->file_path)) {
                return;
            }

            /*
             * Save MIME type.
             */
            $document->file_type = $disk->mimeType(
                $document->file_path
            );

            /*
             * Save file size.
             */
            $document->file_size = $disk->size(
                $document->file_path
            );

            /*
             * Save original filename if empty.
             */
            if (empty($document->original_file_name)) {
                $document->original_file_name = basename(
                    $document->file_path
                );
            }
        });

        /*
         * Delete the physical private file when the document is permanently
         * deleted from the database.
         */
        static::forceDeleted(function (StaffDocument $document): void {

            if (empty($document->file_path)) {
                return;
            }

            $disk = Storage::disk('staff_documents');

            if ($disk->exists($document->file_path)) {
                $disk->delete($document->file_path);
            }
        });
    }
}
