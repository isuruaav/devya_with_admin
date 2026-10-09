<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\MedicalReport;
use App\Models\Patient;
use App\Models\StaffDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateDocumentController extends Controller
{
    public function show(Request $request): StreamedResponse
    {
        $path = $request->query('path');
        $disk = $request->query('disk', 'confidential');

        abort_unless(
            is_string($path)
            && $path !== ''
            && ! str_contains($path, '..')
            && ! str_contains($path, '\\')
            && ! str_starts_with($path, '/'),
            404
        );

        abort_unless(
            in_array($disk, ['confidential', 'staff_documents'], true),
            404
        );

        if ($disk === 'staff_documents') {
            $document = StaffDocument::query()
                ->where('file_path', $path)
                ->firstOrFail();

            abort_unless(
                $request->user()->canAccessModule('staff_management'),
                403
            );

            $name = $document->original_file_name ?: basename($path);
        } elseif (str_starts_with($path, 'patients/reports/')) {
            $document = MedicalReport::query()
                ->where('file_path', $path)
                ->firstOrFail();

            $patient = $document->patient;

            if (! $patient instanceof Patient) {
                abort(404);
            }

            abort_unless(
                $request->user()->canAccessModule('history')
                && $request->user()->canAccessPatient($patient),
                403
            );

            $name = $document->original_file_name ?: basename($path);
        } elseif (str_starts_with($path, 'patients/documents/')) {
            $patient = Patient::query()
                ->where('nic_passport_copy', $path)
                ->firstOrFail();

            abort_unless(
                $request->user()->canAccessPatient($patient),
                403
            );

            $name = basename($path);
        } elseif (
            str_starts_with($path, 'doctors/documents/')
            || str_starts_with($path, 'doctors/slmc-documents/')
        ) {
            Doctor::query()
                ->where('nic_copy', $path)
                ->orWhere('slmc_registration_document', $path)
                ->firstOrFail();

            abort_unless(
                $request->user()->canAccessModule('doctors'),
                403
            );

            $name = basename($path);
        } else {
            abort(404);
        }

        $storage = Storage::disk($disk);

        abort_unless($storage->exists($path), 404);

        return $storage->response($path, $name, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    }
}
