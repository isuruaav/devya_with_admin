<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Patients\PatientResource;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientPhotoController
{
    public function __invoke(Patient $patient): StreamedResponse
    {
        // The same module-level authorization used by the Patient Filament page.
        abort_unless(PatientResource::canAccess(), 403);

        $path = $patient->patient_photo;

        // Accept only filenames from the dedicated profile-photo directory.
        // Do not allow patient documents or arbitrary storage paths.
        abort_unless(
            is_string($path)
                && preg_match('~\Apatients/photos/[A-Za-z0-9][A-Za-z0-9._-]*\.(?:jpe?g|png|webp|gif)\z~i', $path) === 1,
            404
        );

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        $mime = $disk->mimeType($path);

        abort_unless(
            in_array($mime, [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ], true),
            404
        );

        return $disk->response($path, basename($path), [
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
