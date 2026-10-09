<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PublicAdvertisementController extends Controller
{
    public function index(): JsonResponse
    {
        $advertisements = Advertisement::query()
            ->where('is_active', true)

            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })

            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })

            ->orderBy('sort_order')
            ->orderBy('id')

            ->get()

            ->map(function (Advertisement $advertisement) {
                return [
                    'id' => $advertisement->id,
                    'title' => $advertisement->title,
                    'type' => $advertisement->media_type,

                    'url' => Storage::disk('public')
                        ->url($advertisement->media_path),

                    'display_seconds' => max(
                        3,
                        (int) ($advertisement->display_seconds ?? 8)
                    ),
                ];
            })

            ->values();

        return response()->json([
            'advertisements' => $advertisements,
        ]);
    }
}
